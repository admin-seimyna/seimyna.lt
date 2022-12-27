<?php

namespace App\Console\Commands\System;

use App\Enum\Enum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConstantsExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'constants:export';

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
        $disk = Storage::disk('enum');
        $constants = [];
        collect($disk->allFiles())->each(function ($fileName) use ($disk, &$constants) {
            if ($fileName === basename(Enum::class).'.php') {
                return;
            }

            require_once $disk->path($fileName);
            $className = str_replace('.php', '', $fileName);
            $className = Str::snake(str_replace('Enum', '', $className));
            $class = 'App\\Enum\\' . str_replace('.php', '', $fileName);
            $constants[$className] = $class::export($class::all())->toArray();
        });

        Storage::disk('app-resources')->put('js/constants.json', json_encode($constants));
    }
}
