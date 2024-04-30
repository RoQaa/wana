<?php

namespace App\Console\Commands;

use App\Models\Agency;
use App\Models\UserApp;
use Illuminate\Console\Command;

class AgencyMember extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agency:member';

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


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $Agency = Agency::all();
        foreach ($Agency as $sku) {
            $UserApp = UserApp::where("AgencyId", $sku->id)->count();
            $sku->user_number = $UserApp;
            $sku->save();
        }
    }
}
