<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RS_radgroupreply;
use App\Models\RS_service;
use App\Models\RS_radusergroup;
use App\Models\RS_subscriber;


class PolicyController extends Controller
{
       // NAS ADD API 
    public function policyadd(Request $request){
    
        // Validate request data
        $validatedData = $request->validate([
            'groupname' => ['required', 'string', 'max:255'],
            'attribute' => ['required', 'string', 'max:255'],
            'op' => ['required', 'string', 'max:5'],
            'value' => ['required', 'string', 'max:255'],
            'group_id' => ['required'],
            'user_id' => ['required']
        ]);

        // Create the NAS record
        $radgroupreply = RS_radgroupreply::create($validatedData);

        // $radgroupreplyRecords = RS_radgroupreply::all();
        // appendToClientConf($nasRecords);

        // Return a JSON response with the NAS record and a success message
        return response()->json([
            'radgroupreply' => $radgroupreply,
            'message' => 'Radgroupreply successfully added',
            'status' => 1
        ], 200);

    }


     // UPDATE RADGROUPREPLY SINGLE
     public function policyupdate(Request $request){
    
         // Validate request data
         // Validate request data
        $validatedData = $request->validate([
            'groupname' => ['required', 'string', 'max:255'],
            'attribute' => ['required', 'string', 'max:255'],
            'last_attribute' => ['required'],
            'op' => ['required', 'string', 'max:5'],
            'value' => ['required', 'string', 'max:255'],
            'group_id' => ['required'],
            'user_id' => ['required']
        ]);

        // Find the NAS record by ID and ensure it belongs to the authenticated user
        $radgroupreply = RS_radgroupreply::where('groupname', $validatedData['groupname'])
        ->where('attribute', $validatedData['last_attribute'])
        ->where('group_id', $validatedData['group_id'])
        ->where('user_id', $validatedData['user_id'])
        ->first();

        $update_radgroupreply_id = $radgroupreply->id;

        $radgroupreply_update = RS_radgroupreply::where('id', $update_radgroupreply_id)
        ->first();
        

        // Update the NAS record with the validated data
        $radgroupreply_update->update($validatedData);

        // Return a JSON response with the updated NAS record and a success message
        return response()->json([
            'nas' => $radgroupreply_update,
            'message' => 'Policy successfully updated',
            'status' => 1
        ], 200);

    }


    // UPDATE RADGROUPREPLY NAME BULK
     public function policynameupdate(Request $request){
    
       $validatedData = $request->validate([
    'groupname' => ['required', 'string', 'max:255'],
    'group_id' => ['required'],
        ]);

        // Update the groupname in the RS_radgroupreply table
        $radgroupreply_update = RS_radgroupreply::where('group_id', $validatedData['group_id'])
            ->update(['groupname' => $validatedData['groupname']]);



        // FIND Service where user group id

        $service = RS_service::where('policy_id', $validatedData['group_id'])
        ->get();

        foreach ($service as $services) {
                     
                    // FIND USER
                    $user = RS_subscriber::where('srvid',$services->srvid)->get();

                    foreach ($user as $users) {
                     
                    // FIND USER
                    $radusergroup_update = RS_radusergroup::where('username', $users->username)
                        ->update(['groupname' => $validatedData['groupname']]);

            }

        }
        


        // Return a JSON response with the updated NAS record and a success message
        return response()->json([
            'nas' => $radgroupreply_update,
            'message' => 'Policy successfully updated',
            'status' => 1
        ], 200);

    }

     // NAS DELETE API 
     public function policydelete(Request $request){
        // Get the authenticated user
        $user = auth()->user();

        $validatedData = $request->validate([
            'groupname' => ['required', 'string', 'max:255'],
            'attribute' => ['required', 'string', 'max:255'],
            'group_id' => ['required'],
            'user_id' => ['required']
        ]);

        $radgroupreply = RS_radgroupreply::where('groupname', $validatedData['groupname'])
        ->where('attribute', $validatedData['attribute'])
        ->where('group_id', $validatedData['group_id'])
        ->where('user_id', $validatedData['user_id'])
        ->first();


        $delete_radgroupreply_id = $radgroupreply->id;

        $radgroupreply_delete = RS_radgroupreply::where('id', $delete_radgroupreply_id)
        ->first();
    

        if (!$radgroupreply_delete) {
            return response()->json([
                'message' => 'Policy not found or you do not have permission to delete it.',
                'status' => 2
            ], 404);
        }

        // Delete the NAS record
        $radgroupreply_delete->delete();

        // Return a JSON response with a success message
        return response()->json([
            'message' => 'Policy successfully deleted',
            'status' => 1
        ], 200);
    }

    public function policydeletebyid(Request $request, $id)
                {
                    // Get the authenticated user
                    $user = auth()->user();

                    // Get all radgroupreply records for the given group_id
                    $radgroupreply = RS_radgroupreply::where('group_id', $id)->get();

                    // Check if the collection is empty
                    if ($radgroupreply->isEmpty()) {
                        return response()->json([
                            'message' => 'Policy not found or you do not have permission to delete it.',
                            'status' => 1
                        ], 404);
                    }

                    // Delete each record
                    foreach ($radgroupreply as $reply) {
                        $reply->delete();
                    }

                    // Return a JSON response with a success message
                    return response()->json([
                        'message' => 'Policy successfully deleted',
                        'status' => 1
                    ], 200);
                }


    

}
