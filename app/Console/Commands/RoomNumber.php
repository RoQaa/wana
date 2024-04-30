<?php

namespace App\Console\Commands;

use App\Models\Rooms;
use Illuminate\Console\Command;

class RoomNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'room:number';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'RoomNumber';

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

            $Rooms=Rooms::where('state',0)->with('joinRoom')->get();
            for($i = 0;$i<count($Rooms);$i++)
        {
            $Rooms[$i]->user_number=count($Rooms[$i]->joinRoom);
          $Rooms[$i]->save();
        }


    }
}
