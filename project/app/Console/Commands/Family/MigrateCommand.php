<?php

namespace App\Console\Commands\Family;

use App\Models\Family\Family;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'family:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all families';

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
        Auth::login(User::first());
        Family::get()->each(static function (Family $family) {
            $family->connect();
            Artisan::call('migrate --database=' . User::getConnectionKey() . ' --path=database/migrations/family');
        });
        return 0;
    }
}
