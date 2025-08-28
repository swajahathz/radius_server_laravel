<?php

namespace App\Http\Controllers;
use App\Models\RS_radreply;


use Illuminate\Http\Request;

class RadReply extends Controller
{
    public function replyadd(Request $request){

        $validatedData = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'attribute' => ['required'],
            'op' => ['required'],
            'value' => ['required'],
                ]);

        // Create the NAS record
        $radreply = RS_radreply::create($validatedData);

        // $radgroupreplyRecords = RS_radgroupreply::all();
        // appendToClientConf($nasRecords);

        // Return a JSON response with the NAS record and a success message
        return response()->json([
            'radgroupreply' => $radreply,
            'message' => 'Radreply successfully added',
            'status' => 1
        ], 200);

    }

    public function replydelete(Request $request){

        $validatedData = $request->validate([
            'username' => ['required', 'string', 'max:255']
                ]);

        // Create the NAS record
        $radreply_delete = RS_radreply::where('username', $validatedData['username'])
        ->first();

        // Delete the NAS record
        $radreply_delete->delete();

        // Return a JSON response with a success message
        return response()->json([
            'message' => 'Reply successfully deleted',
            'status' => 1
        ], 200);

    }
}
