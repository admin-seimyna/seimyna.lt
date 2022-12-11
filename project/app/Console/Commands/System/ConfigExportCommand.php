<?php

namespace App\Console\Commands\System;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ConfigExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export main app config';

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
        $config = [
            'system' => [
                'app' => [
                    'name' => config('app.name'),
                ],
                'auth' => [
                    'verification' => config('auth.verification')
                ]
            ]
        ];

        Storage::disk('app-resources')->put('js/config.json', json_encode($config));
    }
}
