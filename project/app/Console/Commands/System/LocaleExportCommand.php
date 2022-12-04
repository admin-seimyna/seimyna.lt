<?php

namespace App\Console\Commands\System;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class LocaleExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locale:export';

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
        $translations = [];
        collect(config('app.locales'))->each(static function ($locale) use (&$translations) {
            $translations[$locale] = [];
            collect(Storage::disk('locale')->allFiles($locale))
                ->each(static function ($path) use (&$translations, $locale){
                    [,$filename] = explode('/', $path);
                    [$key,] = explode('.', $filename);

                    $translations[$locale][$key] = include(Storage::disk('locale')->path($path));
                });
        });

        Storage::disk('app-resources')->put('js/i18n/messages.json', json_encode($translations));
    }
}
