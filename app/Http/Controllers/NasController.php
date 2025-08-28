<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\RS_Nas;

class NasController extends Controller
{
    // NAS ADD API 
    public function nasadd(Request $request){
    
        // Validate request data
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'server_ip' => ['required', 'ip'], // Ensuring server_ip is a valid IP address
            'secret' => ['required', 'string', 'max:255'],
            'user_id' => ['required']
        ]);

        $validatedData['shortname'] = $validatedData['name'];
        $validatedData['nasname'] = $validatedData['server_ip'];
        $validatedData['type'] = "Mikro";


        // Create the NAS record
        $nas = RS_Nas::create($validatedData);

        $nasRecords = RS_Nas::all();
        // appendToClientConf($nasRecords);

        // Return a JSON response with the NAS record and a success message
        return response()->json([
            'nas' => $nas,
            'message' => 'NAS successfully added',
            'status' => 1
        ], 200);

    }


     // NAS UPDATE API 
     public function nasupdate(Request $request, $id){
    
         // Validate request data
         $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'server_ip' => ['required', 'ip'], // Ensuring server_ip is a valid IP address
            'secret' => ['required', 'string', 'max:255']
        ]);

        $validatedData['shortname'] = $validatedData['name'];
        $validatedData['nasname'] = $validatedData['server_ip'];
        $validatedData['type'] = "Mikro";



        // Find the NAS record by ID and ensure it belongs to the authenticated user
        $nas = RS_Nas::where('nasname', $id)
        ->first();

        $update_nas_id = $nas->id;

        $nas_update = RS_Nas::where('id', $update_nas_id)
        ->first();
        

        // Update the NAS record with the validated data
        $nas_update->update($validatedData);

        // Return a JSON response with the updated NAS record and a success message
        return response()->json([
            'nas' => $nas_update,
            'message' => 'NAS successfully updated',
            'status' => 1
        ], 200);

    }


     // NAS DELETE API 
     public function nasdelete($id){
        // Get the authenticated user
        $user = auth()->user();

        // Find the NAS record by ID and ensure it belongs to the authenticated user
        $nas = RS_Nas::where('nasname', $id)
        ->first();

        $delete_nas_id = $nas->id;

        $nas_delete = RS_Nas::where('id', $delete_nas_id)
        ->first();
    

        if (!$nas_delete) {
            return response()->json([
                'message' => 'NAS not found or you do not have permission to delete it.',
                'status' => 2
            ], 404);
        }

        // Delete the NAS record
        $nas_delete->delete();

        // Return a JSON response with a success message
        return response()->json([
            'message' => 'NAS successfully deleted',
            'status' => 1
        ], 200);
    }


}
