<?php
/**
 * Created by PhpStorm.
 * User: daltongibbs
 * Date: 7/15/16
 * Time: 10:14 AM
 */

namespace Activelogiclabs\Administration\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;

class MakeAdministrationCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build administration portal for your application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment("Building Admin Portal");

        $filesystem = new Filesystem();

        $files = $filesystem->glob(database_path('migrations').'/*_*.php');

        if ($files === false) {
            return [];
        }

        $files = array_map(function ($file) {
            return str_replace('.php', '', basename($file));
        }, $files);

        sort($files);

        foreach ($files as $file) {

            if (strpos($file, config('admin.users_table')) !== false) {

                break;

            }

        }

        dd($file);
    }

}