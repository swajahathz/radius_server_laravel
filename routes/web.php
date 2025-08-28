<?php

// web latest 4

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\NasController;
use App\Http\Controllers\Bad_logs_Controller;
use App\Http\Controllers\Subscriber_online_Controller;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\sessionController;
use App\Http\Controllers\KickController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\RadReply;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('/subscriber_online/{owner_id}/{all}', [Subscriber_online_Controller::class,'subscriber_online']);
Route::get('/subscriber_online_count/{id}/{roles_id}', [Subscriber_online_Controller::class,'subscriber_online_count']);
Route::get('/subscriber_online_count_numbers/{id}/{roles_id}', [Subscriber_online_Controller::class,'subscriber_online_count_numbers']);

// SINGLE Online Status
Route::post('/subscriber_online_status/{userid}', [Subscriber_online_Controller::class,'subscriber_online_status']);

Route::get('/logs/{owner_id}', [Bad_logs_Controller::class,'bad_logs']);
Route::get('/logs_single/{subscriber}', [Bad_logs_Controller::class,'bad_logs_single']);

// SESSIONS

Route::get('/session_single/{subscriber}', [sessionController::class,'session_single']);

// ADD NAS API

Route::post('/nasadd', [NasController::class,'nasadd']);
Route::post('/nasupdate/{id}', [NasController::class,'nasupdate']);
Route::post('/nasdelete/{id}', [NasController::class,'nasdelete']);


// ADD radgroupreply

Route::post('/radreplyadd', [RadReply::class,'replyadd']);
Route::post('/radreplydelete', [RadReply::class,'replydelete']);



// ADD radgroupreply

Route::post('/radgroupreplyadd', [PolicyController::class,'policyadd']);
Route::post('/radgroupreplyupdate', [PolicyController::class,'policyupdate']);
Route::post('/radgroupreplynameupdate', [PolicyController::class,'policynameupdate']);
Route::post('/radgroupreplydelete', [PolicyController::class,'policydelete']);
Route::post('/radgroupreplydeletebyid/{id}', [PolicyController::class,'policydeletebyid']); // CREATE for Policy delete function bulk delete


// ADD SERVICE API

Route::post('/serviceadd', [ServiceController::class,'serviceadd']);
Route::post('/serviceidupdate/{code}', [ServiceController::class,'serviceidupdate']);
Route::post('/serviceupdate/{srvid}', [ServiceController::class,'serviceupdate']);
Route::post('/poolupdate/{srvid}', [ServiceController::class,'poolupdate']);
Route::post('/servicedelete/{srvid}', [ServiceController::class,'servicedelete']);


Route::get('/subscriber', [SubscriberController::class,'subscriber']);
Route::post('/addsubscriber', [SubscriberController::class,'addsubscriber']);
Route::post('/updatesubscriberpassword/{username}', [SubscriberController::class,'updatesubscriberpassword']);
Route::post('/updatesubscriberservice/{username}', [SubscriberController::class,'updatesubscriberservice']);
Route::post('/updatesubscriberexpiration/{username}', [SubscriberController::class,'updatesubscriberexpiration']);
Route::post('/updatesubscriberenable/{username}', [SubscriberController::class,'updatesubscriberenable']);
Route::post('/subscriberdelete/{username}', [SubscriberController::class,'subscriberdelete']);



Route::post('/kick/{username}', [KickController::class,'kick']);