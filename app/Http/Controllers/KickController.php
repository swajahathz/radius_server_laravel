<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RS_session;
use App\Models\RS_Nas;

class KickController extends Controller
{
    public function kick(Request $request, $username)
    {
        // FIND User Detail
        $user_details = RS_session::where('username', $username)
        ->orderBy('radacctid', 'DESC')
        ->first();

        $nas = $user_details->nasipaddress;

        // FIND NAS DETAILS
        $nas_details = RS_Nas::where('nasname', $nas)
        ->first(); 
        
        $port = $nas_details->incoming_port;
        $secret = $nas_details->secret;


        $command = "echo User-Name=$username | /usr/bin/radclient -x $nas:$port disconnect $secret";

        // Execute and capture output and return code
        exec($command, $output, $returnVar);

        return response()->json([
            'command' => $command,
            'output' => $output,
            'status' => $returnVar === 0 ? 'success' : 'error'
        ]);
    }
}
