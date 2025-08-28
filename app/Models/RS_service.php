<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RS_service extends Model
{
    use HasFactory;
    // Disable timestamps
    public $timestamps = false;

    protected $table = 'services';

    protected $fillable = [
        'downrate',
        'uprate',
        'pool_id',
        'policy_id',
        'pool_name',
        'custattr',
        'user_id',
        'code',
        'srvid'
    ];
}
