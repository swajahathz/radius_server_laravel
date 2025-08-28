<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class RS_subscriber extends Model
{
    use HasFactory;

    // Disable timestamps
    public $timestamps = false;

    protected $table = 'subscriber';

    protected $fillable = [
        'subscriber_enable',
        'username',
        'password',
        'expiration',
        'srvid',
        'ownerId',
        'adminId',
        'franchiseId',
        'dealerId',
        'subdealerId'
    ];
}
