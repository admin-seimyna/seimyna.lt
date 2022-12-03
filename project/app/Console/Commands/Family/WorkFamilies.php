<?php

namespace App\Console\Commands\Family;

use App\Models\Family\Family;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkFamilies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'family:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Working working';

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
//        Family::get()->each(static function (Family $family) {
//            DB::statement('drop database ' . $family->connection_name);
//        });
        $user = User::first();
        Auth::login($user);

        dd($user->family->toArray());

        return 0;
    }
}
