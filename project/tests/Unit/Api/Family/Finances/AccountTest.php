<?php

namespace Api\Family\Finances;

use App\Models\Bank;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function testCanCreateNordigenAccount()
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

        $response = $this->get(route('api.finances.nordigen.accounts', [
            'requisition' => $requisition->id
        ]))->assertStatus(200);

        $data = json_decode($response->getContent(), true);

        $this->post(route('api.finances.account.create'), [
            'accounts' => $data
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'uid', 'iban', 'requisition_id', 'member_id', 'updated_at', 'created_at']
            ]);
    }

    public function testCanCreateVirtualAccount()
    {
        $this->login()->connectFamily();
        Artisan::call('nordigen:banks-sync');
        $bank = Bank::first();
        $this->post(route('api.finances.account.create'), [
            'accounts' => [
                [
                    'name' => 'test',
                    'iban' => 'LT45464564',
                    'bank_id' => $bank->id,
                ]
            ]
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'uid', 'iban', 'requisition_id', 'member_id', 'updated_at', 'created_at']
            ]);
    }
}
