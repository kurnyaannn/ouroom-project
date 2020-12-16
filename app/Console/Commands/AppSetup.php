<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AppSetup extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classroom:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proses Instalasi Classroom';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $this->line('--------------- Instalasi Classroom ----------------');

        $this->line('Deleting old Database.');
        Artisan::call('database:delete', ['db_name' => env('DB_DATABASE')]);

        $this->line('Creating new Database.');
        Artisan::call('database:create', ['db_name' => env('DB_DATABASE')]);

        $this->line('Running Migration:fresh.');
        Artisan::call('database:migrate:fresh');

        $this->line('Injecting User to Database.');
        Artisan::call('database:user:seed');

        $this->line('Injecting Role to Database.');
        Artisan::call('database:role:seed');

        $this->line('Making Laravel Key.');
        Artisan::call('key:generate');

        $path = public_path() . '/storage';

        $this->line('Checking Storage Link');

        if (!file_exists($path)) {
            Artisan::call('storage:link');
            $this->line('Making Storage Succes.');
        } else {
            $this->line('Storage Ready.');
        }

        $this->line('Installation Complete.');
    }
}
