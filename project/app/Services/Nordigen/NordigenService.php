<?php

namespace App\Services\Nordigen;

use App\Models\Family\Finances\BankAccount;
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
     * @param string $bankId
     * @param string|null $agreementId
     * @return array
     */
    public function createRequisition(string $bankId, ?string $agreementId = null): array
    {
        $user = Auth::user();
        $reference = $user->currentFamily->id . '_' . Auth::user()->member->id;
        if (app()->runningUnitTests()) {
            $reference = time().uniqid();
        }

        $data = [
            'redirect'  => config('services.nordigen.redirect'),
            'institution_id'  => $bankId,
            'reference'  => $reference,
            'user_language'  => config('app.locale'),
        ];

        if ($agreementId) {
            $data['agreement'] = $agreementId;
        }
        return $this->client->post('/requisitions/', $data);
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
