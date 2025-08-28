<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RS_service;
use App\Models\RS_subscriber;
use App\Models\RS_radgroupreply;
use App\Models\RS_radusergroup;


class ServiceController extends Controller
{
    // ADD API 
    public function serviceadd(Request $request){
    
        // Validate request data
        $validatedData = $request->validate([
            'user_id' => ['required'],
            'code' => ['required'],
            'downrate' => ['required', 'string', 'max:255'],
            'uprate' => ['required', 'string', 'max:255'],
            'pool_id' => ['required'],
            'policy_id' => ['required'],
            'pool_name' => ['required', 'string', 'max:255'],
            'custattr' => ['nullable', 'string', 'max:255']
        ]);

        // $validatedData['shortname'] = $validatedData['name'];
        // $validatedData['nasname'] = $validatedData['server_ip'];
        // $validatedData['type'] = "Mikro";


        // Create the NAS record
        $services = RS_service::create($validatedData);

        $servicesRecords = RS_service::all();
        // appendToClientConf($nasRecords);

        // Return a JSON response with the NAS record and a success message
        return response()->json([
            'services' => $services,
            'message' => 'Service successfully added',
            'status' => 1
        ], 200);

    }

     // NAS UPDATE API 
     public function serviceupdate(Request $request, $srvid){
    
        // Validate request data
        $validatedData = $request->validate([
            'downrate' => ['required', 'string', 'max:255'],
            'uprate' => ['required', 'string', 'max:255'],
            'pool_id' => ['required', 'string', 'max:255'],
            'policy_id' => ['required', 'string', 'max:255'],
            'pool_name' => ['required', 'string', 'max:255'],
            'custattr' => ['nullable', 'string', 'max:255']
        ]);


       // Find the record by ID and ensure it belongs to the authenticated user
       $service = RS_service::where('srvid', $srvid)
       ->first();

       $update_service_id = $service->id;

       $service_update = RS_service::where('id', $update_service_id)
       ->first();


       if($validatedData['policy_id'] == 0){

            // FIND SERVICE USERS
            $users_list = RS_subscriber::where('srvid',$srvid)->get();


            foreach ($users_list as $user) {
                     RS_radusergroup::where('username', $user->username)->delete();
            }
            

       }
       else{

            $groupname = RS_radgroupreply::where('group_id',$validatedData['policy_id'])->first();

            $users_list = RS_subscriber::where('srvid',$srvid)->get();

            foreach ($users_list as $user) {
                     RS_radusergroup::where('username', $user->username)->delete();
            }

            foreach ($users_list as $user) {

                   RS_radusergroup::create([
                            'username'  => $user->username,
                            'groupname' => $groupname->groupname
                        ]);
            }


       }
       

       // Update the NAS record with the validated data
       $service_update->update($validatedData);

       // Return a JSON response with the updated NAS record and a success message
       return response()->json([
           'service' => $service_update,
           'message' => 'Service successfully updated',
           'status' => 1
       ], 200);

   }
   public function poolupdate(Request $request, $poolid){
    
    // Validate request data
    $validatedData = $request->validate([
        'pool_name' => ['required', 'string', 'max:255'],
    ]);


   // Find the record by ID and ensure it belongs to the authenticated user
   $service = RS_service::where('pool_id', $poolid)->update($validatedData);

//    $update_service_id = $service->id;

//    $service_update = RS_service::where('id', $update_service_id)
//    ->first();
   

//    // Update the NAS record with the validated data
//    $service_update->update($validatedData);

   

   // Return a JSON response with the updated NAS record and a success message
   return response()->json([
       'service' => $service,
       'message' => 'Pool name successfully updated',
       'status' => 1
   ], 200);

}

    // service id UPDATE API 
    public function serviceidupdate(Request $request, $code){
        
        // Validate request data
        $validatedData = $request->validate([
        'srvid' => ['required']
             ]);

        // Find the NAS record by ID and ensure it belongs to the authenticated user
        $service = RS_service::where('code', $code)
        ->first();

        $update_service_id = $service->id;

        $service_update = RS_service::where('id', $update_service_id)
        ->first();
        

        // Update the NAS record with the validated data
        $service->update($validatedData);

        // Return a JSON response with the updated NAS record and a success message
        return response()->json([
            'nas' => $service,
            'message' => 'Service ID successfully updated',
            'status' => 1,
            'id' => $update_service_id
        ], 200);

    }


    // NAS DELETE API 
    public function servicedelete($srvid){
        // Get the authenticated user
        $user = auth()->user();

        // Find the NAS record by ID and ensure it belongs to the authenticated user
        $service = RS_service::where('srvid', $srvid)
        ->first();

        $delete_service_id = $service->id;

        $service_delete = RS_service::where('id', $delete_service_id)
        ->first();
    

        if (!$service_delete) {
            return response()->json([
                'message' => 'Service not found or you do not have permission to delete it.',
                'status' => 2
            ], 404);
        }

        // Delete the NAS record
        $service_delete->delete();

        // Return a JSON response with a success message
        return response()->json([
            'message' => 'Service successfully deleted',
            'status' => 1
        ], 200);
    }

}
