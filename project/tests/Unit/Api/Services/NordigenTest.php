<?php

namespace Api\Services;

use App\Models\Bank;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class NordigenTest extends TestCase
{
    use RefreshDatabase;

    public function testNordigenService()
    {
        $this->login()->connectFamily();
        Artisan::call('nordigen:banks-sync');

        $bank = Bank::where('bic', env('NORDIGEN_BANK_BIC'))->first();
        $requisition = Auth::user()->member->requisition()->create([
            'uid' => env('NORDIGEN_REQUISITION'),
            'bank_id' => $bank->id,
            'link' => 'test',
            'activated_at' => Carbon::now()
        ]);

        $this->post(route('api.finances.nordigen.requisition', [
            'bank' => $bank->id
        ]))->assertStatus(200)
            ->assertJsonStructure([
                'id', 'link'
            ]);

        $response = $this->get(route('api.finances.nordigen.accounts', [
            'requisition' => $requisition->id
        ]))->assertStatus(200)
            ->assertJsonStructure(['*' => ['uid', 'created_at', 'iban', 'bank_id', 'requisition_id']]);

        $accounts = json_decode($response->getContent(), true);
        Auth::user()->member->bankAccount()->create($accounts[1]);

        $this->get(route('api.finances.nordigen.transactions', [
            'account' => Auth::user()->member->bankAccount->id
        ]))->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'uid', 'bank_account_id', 'date', 'amount', 'code', 'description', 'status',
                    'creator' => ['name', 'iban'],
                    'debtor' => ['name', 'iban']
                ]
            ]);

        $this->get(route('api.finances.nordigen.balance', [
            'account' => Auth::user()->member->bankAccount->id
        ]))->assertStatus(200)
            ->assertJsonStructure([
                'bank_account_id', 'amount', 'date'
            ]);
    }

}
