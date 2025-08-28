<?php

// LATEST UDPDATE 3

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RS_subscriber_online;
use Illuminate\Support\Facades\DB;

class Subscriber_online_Controller extends Controller
{
    // User LIST API 
    public function subscriber_online(Request $request, $owner_id,$all){


        if($all != 1){

            if($all == 2){
                    $m = "adminId";
            }elseif($all == 3){
                    $m = "franchiseId";
            }elseif($all == 4){
                    $m = "dealerId";
            }elseif($all == 5){
                    $m = "subdealerId";
            }elseif($all == 5){
                    $m = "juniordealerId";
            }

            $users = RS_subscriber_online::where('acctstoptime',null)
                                            ->where($m, $owner_id)
                                            ->get();

                
        }else{
               $users = RS_subscriber_online::where('acctstoptime',null)
                        ->where('ownerId', $owner_id)
                        ->get();     
        }


        


       // Check if NAS records are found
       if($users->isEmpty()){
           return response()->json(['message' => 'Users not found!'], 200);
       } else {
           return response()->json($users, 200);
       }
   
    }   


    // User LIST API 
    public function subscriber_online_status(Request $request, $user_id){


        $users = RS_subscriber_online::where('acctstoptime',null)
        ->where('username',$user_id)
        ->get();


       // Check if NAS records are found
       if($users->isEmpty()){
           return response()->json(['message' => '0',
           'status' =>1], 200);
       } else {
        return response()->json(['message' => '1',
        'user_detail' => $users,
        'status' =>1], 200);
       }
   
    }  

     // User LIST API 
     public function subscriber_online_count(Request $request,$id,$roles_id){


        if($roles_id == 2){
                    $users = RS_subscriber_online::where('acctstoptime',null)
                            ->where('adminId',$id)
                            ->get();
        }

        if($roles_id == 3){
                    $users = RS_subscriber_online::where('acctstoptime',null)
                            ->where('franchiseId',$id)
                            ->get();
        }

        if($roles_id == 4){
                    $users = RS_subscriber_online::where('acctstoptime',null)
                            ->where('dealerId',$id)
                            ->get();
        }

        if($roles_id == 5){
                    $users = RS_subscriber_online::where('acctstoptime',null)
                            ->where('subdealerId',$id)
                            ->get();
        }

        if($roles_id == 6){
                    $users = RS_subscriber_online::where('acctstoptime',null)
                            ->where('ownerId',$id)
                            ->get();
        }

        


       // Check if NAS records are found
       if($users->isEmpty()){
           return response()->json($users, 200);
       } else {
           return response()->json($users, 200);
       }
   
    }   

    public function subscriber_online_count_numbers(Request $request, $id, $roles_id)
        {
            // Query builder base
            $query = RS_subscriber_online::query()
                ->join('subscriber', 'radacct.username', '=', 'subscriber.username')
                ->select(
                    DB::raw('COUNT(*) as total_online'),
                DB::raw('SUM(CASE WHEN STR_TO_DATE(subscriber.expiration, "%d %b %Y %H:%i") > NOW() THEN 1 ELSE 0 END) as active_users'),
                DB::raw('SUM(CASE WHEN STR_TO_DATE(subscriber.expiration, "%d %b %Y %H:%i") < NOW() THEN 1 ELSE 0 END) as expire_users'),
                    DB::raw('SUM(CASE WHEN subscriber.subscriber_enable = 0 THEN 1 ELSE 0 END) as disabled_users')
                )
                ->whereNull('radacct.acctstoptime'); // sirf online users

            // Role based filter
            if ($roles_id == 2) {
                $query->where('radacct.adminId', $id);
            } elseif ($roles_id == 3) {
                $query->where('radacct.franchiseId', $id);
            } elseif ($roles_id == 4) {
                $query->where('radacct.dealerId', $id);
            } elseif ($roles_id == 5) {
                $query->where('radacct.subdealerId', $id);
            } elseif ($roles_id == 6) {
                $query->where('radacct.ownerId', $id);
            }

            $onlineData = $query->first();

            // Offline count nikalna (jo acctstoptime null nahi hain)
           $offlineQuery = DB::table('subscriber')
                    ->whereRaw('STR_TO_DATE(subscriber.expiration, "%d %b %Y %H:%i") > NOW()')
                    ->where('subscriber.subscriber_enable', 1)
                    ->whereNotIn('subscriber.username', function ($q) {
                        $q->select('username')
                            ->from('radacct')
                            ->whereNull('acctstoptime');
                    });

                if ($roles_id == 2) {
                    $offlineQuery->where('subscriber.adminId', $id);
                }  elseif ($roles_id == 3) {
                    $q->where('subscriber.franchiseId', $id);
                } elseif ($roles_id == 4) {
                    $q->where('subscriber.dealerId', $id);
                } elseif ($roles_id == 5) {
                    $q->where('subscriber.subdealerId', $id);
                } elseif ($roles_id == 6) {
                    $q->where('subscriber.ownerId', $id);
                }
            // ->whereNotIn('subscriber.username', function ($q) use ($roles_id, $id) {
            //     $q->select('username')
            //         ->from('radacct')
            //         ->whereNull('acctstoptime'); // sirf online users

            //     if ($roles_id == 2) {
            //         $q->where('radacct.adminId', $id);
            //     } elseif ($roles_id == 3) {
            //         $q->where('radacct.franchiseId', $id);
            //     } elseif ($roles_id == 4) {
            //         $q->where('radacct.dealerId', $id);
            //     } elseif ($roles_id == 5) {
            //         $q->where('radacct.subdealerId', $id);
            //     } elseif ($roles_id == 6) {
            //         $q->where('radacct.ownerId', $id);
            //     }
            // });



        // Agar sirf count chahiye:
        $offlineCount = $offlineQuery->count();

            return response()->json([
                'total_online'   => $onlineData->total_online,
                'active_users'   => $onlineData->active_users,
                'expire_users'   => $onlineData->expire_users,
                'disabled_users' => $onlineData->disabled_users,
                'offline_users'  => $offlineCount,
                'roles_id' => $roles_id
            ], 200);
        }
        
}
