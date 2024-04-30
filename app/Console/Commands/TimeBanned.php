<?php

namespace App\Console\Commands;

use App\Models\baned_devices;
use App\Models\UserApp;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TimeBanned extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'time:banned';

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

 
        $Baned = baned_devices::where('kind', 1)->get();
        foreach ($Baned as $cl) {
            $days = Carbon::now()->diffInDays($cl->created_at, false);
            if ($days < 0) {
                $user = UserApp::find($cl->user_id);
                $user->update(['ban' => 0]);

                baned_devices::where('user_id', $cl->user_id)->delete();
            }
        }
    }
}
