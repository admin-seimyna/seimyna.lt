<?php

namespace App\Services\Nordigen;

use App\Models\Bank;
use App\Models\Family\Finances\BankAccount;
use App\Models\Family\Finances\Requisition;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class NordigenService
{
    /**
     * @var NordigenClient
     */
    protected NordigenClient $client;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new NordigenClient();
    }

    /**
     * @return static
     */
    public static function connect(): self
    {
        return new static();
    }

    /**
     * @param string $country
     * @return Collection
     */
    public function getBanksList(string $country = 'LT'): Collection
    {
        return collect($this->client->get('/institutions', [
            'country' => $country
        ]))->map(static function (array $data) {
            return NordigenBank::parse($data);
        });
    }

    /**
     * @param Requisition $requisition
     * @return array
     */
    public function getRequisition(Requisition $requisition): array
    {
        $user = Auth::user();
        $data = [
            'redirect'  => route('api.requisition.activate', [
                'familyId' => $user->currentFamily->id,
                'userId' => $user->id,
            ]),
            'institution_id'  => $requisition->bank->uid,
            'reference'  => $requisition->ref,
            'user_language'  => config('app.locale'),
        ];

        return NordigenRequisition::parse($this->client->post('/requisitions/', $data));
    }

    /**
     * @param string $requisitionId
     * @return array
     */
    public function requisition(string $requisitionId): array
    {
        return $this->client->get('/requisitions/' . $requisitionId);
    }

    /**
     * @param string $accountId
     * @param int $requisitionId
     * @return array
     */
    public function details(string $accountId, int $requisitionId): array
    {
        return NordigenAccount::parse($this->client->get('/accounts/' . $accountId), $requisitionId);
    }

    /**
     * @param BankAccount $account
     * @return array
     */
    public function transactions(BankAccount $account): array
    {
        return collect($this->client->get('/accounts/' . $account->uid . '/transactions')['transactions'])
            ->map(static function (array $transactions, $status) use ($account) {
                return collect($transactions)
                    ->map(static function (array $transaction) use ($account, $status) {
                        return NordigenTransaction::parse($transaction, $status, $account->id);
                    });
            })->collapse()->toArray();
    }

    /**
     * @param BankAccount $account
     * @return array
     */
    public function balance(BankAccount $account): array
    {
        return collect($this->client->get('/accounts/' . $account->uid . '/balances')['balances'])
            ->where('balanceType', 'interimAvailable')
            ->map(static function (array $balance) use ($account) {
                return NordigenBalanace::parse($balance, $account);
            })->first();
    }
}
