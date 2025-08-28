<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RS_subscriber;
use App\Models\RS_service;
use App\Models\RS_radgroupreply;
use App\Models\RS_radusergroup;
use App\Models\RS_radcheck;

class SubscriberController extends Controller
{
    // User LIST API 
    public function subscriber(Request $request){


              $users = RS_subscriber::all();


             // Check if NAS records are found
             if($users->isEmpty()){
                 return response()->json(['message' => 'Users not found!'], 200);
             } else {
                 return response()->json($users, 200);
             }
         
    }

    // ADD Subscriber

    public function addsubscriber(Request $request){

          // Validate request data
        $validatedData = $request->validate([
            'username' => 'required|string|max:64',
            'password' => 'required|string|max:40',
            'expiration' => 'required|string|max:40',
            'srvid' => 'required|integer',
            'ownerId' => 'required|integer',
            'adminId' => 'required|integer',
            'franchiseId' => 'nullable|integer',
            'dealerId' => 'nullable|integer',
            'subdealerId' => 'nullable|integer'
            
        ]);

        // $validatedData['shortname'] = $validatedData['name'];
        // $validatedData['nasname'] = $validatedData['server_ip'];
        // $validatedData['type'] = "Mikro";

        // FIND SERVICE
        $service = RS_service::where('srvid',$validatedData['srvid'])->first();

        if($service->policy_id != 0){

            // FIND GROUP
            $group = RS_radgroupreply::where('group_id',$service->policy_id)->first();

            RS_radusergroup::create([
                                'username'  => $validatedData['username'],
                                'groupname' => $group->groupname
                            ]);


        }

          // Create the NAS record
            $subscriber = RS_subscriber::create($validatedData);

        $subscriberRecords = RS_subscriber::all();

        // Return a JSON response with the NAS record and a success message
        return response()->json([
            'nas' => $subscriber,
            'message' => 'Subsriber successfully added',
            'status' => 1
        ], 200);
    }

     public function subscriberdelete($id){
        // Get the authenticated user
        $user = auth()->user();

        // Find the NAS record by ID and ensure it belongs to the authenticated user
        $subscriber = RS_subscriber::where('username', $id)
        ->first();

        if (!$subscriber) {
            return response()->json([
                'message' => 'Subscriber not found or you do not have permission to delete it.',
                'status' => 2
            ], 404);
        }

        $delete_subscriber_id = $subscriber->id;

        $subscriber_delete = RS_subscriber::where('id', $delete_subscriber_id)
        ->first();
    

        

        // Delete the NAS record
        RS_radusergroup::where('username',$id)->delete();
        RS_radcheck::where('username',$id)->delete();
        RS_subscriber::where('username',$id)->delete();

        // Return a JSON response with a success message
        return response()->json([
            'message' => 'Subscriber Delete successfully deleted',
            'status' => 1
        ], 200);
    }


    public function updatesubscriberpassword(Request $request, $username){

        // Validate request data
      $validatedData = $request->validate([
          'password' => 'required|string|max:20',
      ]);

      $subscriber = RS_subscriber::where('username', $username)
       ->first();

       $update_subscriber_id = $subscriber->id;

       $subscriber_update = RS_subscriber::where('id', $update_subscriber_id)
       ->first();


       $subscriber_update->update($validatedData);

      // Return a JSON response with the NAS record and a success message
      return response()->json([
          'subscriber' => $subscriber_update,
          'message' => 'Subsriber successfully updated',
          'status' => 1
      ], 200);
  }

    public function updatesubscriberservice(Request $request, $username){

                    // Validate request data
                $validatedData = $request->validate([
                    'srvid' => 'required|string|max:20',
                ]);

                $subscriber = RS_subscriber::where('username', $username)
                ->first();

                $update_subscriber_id = $subscriber->id;

                $subscriber_update = RS_subscriber::where('id', $update_subscriber_id)
                ->first();

                // FIND SRVID policy id
                $service = RS_service::where('srvid',$validatedData['srvid'])->first();

                // FIND GROUP NAME
                $group = RS_radgroupreply::where('group_id',$service->policy_id)->first();

                if($service->policy_id == 0){

                    RS_radusergroup::where('username',$username)->delete();

                }else{

                    RS_radusergroup::where('username',$username)->delete();
                    RS_radusergroup::create([
                                'username'  => $username,
                                'groupname' => $group->groupname
                            ]);
                }


                $subscriber_update->update($validatedData);

                // Return a JSON response with the NAS record and a success message
                return response()->json([
                    'subscriber' => $subscriber_update,
                    'message' => 'Subsriber successfully updated',
                    'status' => 1
                ], 200);
    }

    public function updatesubscriberexpiration(Request $request, $username){

        // Validate request data
        $validatedData = $request->validate([
            'expiration' => 'required|string|max:20',
        ]);

        $subscriber = RS_subscriber::where('username', $username)
        ->first();

        $update_subscriber_id = $subscriber->id;

        $subscriber_update = RS_subscriber::where('id', $update_subscriber_id)
        ->first();


        $subscriber_update->update($validatedData);

        // Return a JSON response with the NAS record and a success message
        return response()->json([
            'subscriber' => $subscriber_update,
            'message' => 'Subsriber successfully updated',
            'status' => 1
        ], 200);
    }

    public function updatesubscriberenable(Request $request, $username){

        // Validate request data
        $validatedData = $request->validate([
            'subscriber_enable' => 'required',
        ]);

        $subscriber = RS_subscriber::where('username', $username)
        ->first();

        $update_subscriber_id = $subscriber->id;

        $subscriber_update = RS_subscriber::where('id', $update_subscriber_id)
        ->first();


        $subscriber_update->update($validatedData);

        // Return a JSON response with the NAS record and a success message
        return response()->json([
            'subscriber' => $subscriber_update,
            'message' => 'Subsriber successfully updated',
            'status' => 1
        ], 200);
    }
}
