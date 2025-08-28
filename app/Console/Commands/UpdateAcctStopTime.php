<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateAcctStopTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acct:update-stop-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update acctstoptime if acctupdatetime is older than 10 minutes';

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
        $rows = DB::table('radacct')
        ->whereNull('acctstoptime')
        ->where('acctupdatetime', '<', Carbon::now()->subMinutes(10))
        ->get();

        foreach ($rows as $row) {
            // Step 1: Update acctstoptime
            $row->acctstoptime = Carbon::now();


            // Convert to array
                $rowArray = (array) $row;

                // Remove radacctid before insert/update
                $rowArray = Arr::except($rowArray, ['radacctid']);

            //Exixt
            $find = DB::table('radacct_accounting')->where('acctuniqueid',$row->acctuniqueid)->first();

            // if not find insert else update where acctuniqueid 
            if(!$find){
                 // Step 2: Insert into radacct_accounting
                 DB::table('radacct_accounting')->insert((array) $rowArray);
            }else{
                 // Update
                    DB::table('radacct_accounting')
                        ->where('acctuniqueid', $row->acctuniqueid)
                        ->update((array) $rowArray);
            }


           

            // Step 3: Delete from radacct
            DB::table('radacct')->where('acctsessionid', $row->acctsessionid)->delete();
        }

        $this->info('Moved inactive sessions to radacct_accounting.');
    }
}
