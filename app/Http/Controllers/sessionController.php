<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RS_session;

class sessionController extends Controller
{
    public function session_single(Request $request, $subscriber)
{
    $session = RS_session::where('username', $subscriber)->get();

    if (!$session) { // Check if no record is found
        return response()->json(['message' => 'Session not found!'], 200);
    }

    return response()->json($session, 200);
}
}
