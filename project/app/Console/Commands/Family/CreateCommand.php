<?php

namespace App\Console\Commands\Family;

use App\Models\Family\Family;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'family:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new family';

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
        $name = 'Navickai';
        /**
         * @var Family $family
         */
        $family = Family::create(['name' => $name]);
        DB::statement('create database ' . $family->connection_name);
        $user = User::first();
        if (!$user) {
            $user = User::create(['name' => 'Admin', 'password' => Hash::make('4asd54641326456'), 'email' => 'admin@seimyna.lt']);
        }
        Auth::login($user);
        $family->connect();
        Artisan::call('migrate --database=' . User::getConnectionKey() . ' --path=database/migrations/family');
    }
}
