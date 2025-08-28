<?php

// wprlogm

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RS_Bad_logs;

class Bad_logs_Controller extends Controller
{
    // logs LIST API 
    public function bad_logs(Request $request,$owner_id){


       $logs = RS_Bad_logs::where('ownerId', $owner_id)
    ->orderBy('id', 'desc')
    ->get();


       // Check if NAS records are found
       if($logs->isEmpty()){
           return response()->json($logs, 200);
       } else {
           return response()->json($logs, 200);
       }
   
}

// logs LIST API 
public function bad_logs_single(Request $request, $subscriber)
{
    $logs = RS_Bad_logs::where('username', $subscriber)->get();

    if (!$logs) { // Check if no record is found
        return response()->json(['message' => 'Logs not found!'], 200);
    }

    return response()->json($logs, 200);
}
}
