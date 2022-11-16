<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SetupProdEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prod:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets up the a fresh production environment';

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
     * @return mixed
     */
    public function handle()
    {
        $this->info('Setting up a fresh production environment');

        //fresh migration
        $this->call('migrate:fresh');

        $this->CreatePersonalAccessClient();

        // dump sql db
        // $cmd =
        //     "mysqldump -h " . env('DB_HOST') .
        //     " -u "          . env('DB_USERNAME') .
        //     " -p\""         . env('DB_PASSWORD') . "\"" .
        //     " --databases " . env('DB_DATABASE');
        
        // $dir = __DIR__.'/../../../database/backups/'.date("Y-m-d").'/';        

        // $output = [];
        
        // exec($cmd, $output);
        
        // if(!file_exists($dir)){
        //     @mkdir($name,0777,true);
        // }        

        // $filename =  $dir.md5(time()).'.sql';

        // $handle = fopen($filename, "w");

        // file_put_contents($filename, implode($output, "\n"));

        $this->info('All done. Bye!');
    }

    public function CreatePersonalAccessClient()
    {
        $this->call('passport:client', [
            '--personal' => true,
            '--name'     => 'Production Personal Access Client',
        ]);
    }

}
