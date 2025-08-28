<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RS_radusergroup extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'radusergroup';

    protected $fillable = [
        'groupname',
        'username'
    ];
}
