<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RS_subscriber_online;
use App\Models\RS_service_shuduler;
use App\Models\RS_service;
use App\Models\RS_Nas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ApplyBandwidthCommand extends Command
{
    protected $signature = 'bandwidth:apply';
    protected $description = 'Apply bandwidth based on schedule or default service attribute';

    public function handle()
    {
         $now = Carbon::now()->format('H:i:s');

         
 // Get all schedule entries
        $allSchedules = RS_service_shuduler::all();

        foreach ($allSchedules as $schedule) {
            // Check if current time is within the schedule
            if ($now >= $schedule->starttime && $now <= $schedule->endtime) {
                // Set status = 1 (active)
                $schedule->status = 1;
                $schedule->save();

                // FIND USER ID FOR CHANGE BANDWITH

               $users = RS_subscriber_online::where('srvid', $schedule->srvid)
               ->where('srvch_id', '!=', $schedule->id)
                            ->get();

                        foreach ($users as $user) {
                           $username = $user->username;

                            $this->info($username);


                            $nas = RS_Nas::where('nasname',$user->nasipaddress)->first();
                            $nas_ip = $nas->nasname;
                            $secret = $nas->secret;
                            $port = $nas->incoming_port;

                            

                            // Define your attribute (for example, a Mikrotik rate-limit or address-list)
                            $attribute = "Mikrotik-Rate-Limit := {$schedule->downrate}k/{$schedule->uprate}k";
                            
                            $cmd = "echo \"User-Name = $username, $attribute\" | /usr/bin/radclient -x $nas_ip:$port coa $secret";
                            exec($cmd, $output, $status);

                            if ($status === 0) {
                                // Update the current user's schedule ID
                                $user->srvch_id = $schedule->id;
                                $user->save();
                                $this->info("CoA sent to $username with $attribute");
                            } else {
                                $this->error("Failed to send CoA to $username");
                            }
                        }


                $this->info("Activated SRVID {$schedule->srvid} with bandwidth: {$schedule->downrate}");
            } else {

               $users = RS_subscriber_online::where('srvid', $schedule->srvid)
                        ->where('srvch_id', '=', $schedule->id)
                        ->get();

                        foreach ($users as $user) {
                           $username = $user->username;

                            $this->info($username);


                            $nas = RS_Nas::where('nasname',$user->nasipaddress)->first();
                            $nas_ip = $nas->nasname;
                            $secret = $nas->secret;
                            $port = $nas->incoming_port;

                            //FIND DEFAULT SPEED
                            $service = RS_service::where('srvid',$schedule->srvid)->first();

                            // Define your attribute (for example, a Mikrotik rate-limit or address-list)
                            $attribute = "Mikrotik-Rate-Limit := {$service->downrate}k/{$service->uprate}k";
                            
                            $cmd = "echo \"User-Name = $username, $attribute\" | /usr/bin/radclient -x $nas_ip:$port coa $secret";
                            exec($cmd, $output, $status);

                            if ($status === 0) {
                                // Update the current user's schedule ID
                                $user->srvch_id = 0;
                                $user->save();

                                $this->info("CoA sent to $username with $attribute");
                            } else {
                                $this->error("Failed to send CoA to $username");
                            }
                        }

                        
                // Set status = 0 (inactive)
                $schedule->status = 0;
                $schedule->save();

                $this->info("Deactivated SRVID {$schedule->id}");
            }
        }

        return 0;
    }
    
}
