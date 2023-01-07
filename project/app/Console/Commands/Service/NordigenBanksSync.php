<?php

namespace App\Console\Commands\Service;

use App\Models\Bank;
use App\Services\Nordigen\NordigenService;
use Illuminate\Console\Command;

class NordigenBanksSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nordigen:banks-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = NordigenService::connect()->getBanksList();
        $response->each(static function (array $bank) {
            Bank::updateOrCreate([
                'uid' => $bank['uid']
            ], $bank);
        });
    }
}
