<?php

namespace App\Console\Commands\System;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class BuildCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('locale:export');
        Artisan::call('config:export');
        Artisan::call('constants:export');
    }
}
