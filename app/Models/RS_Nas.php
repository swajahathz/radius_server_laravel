<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RS_Nas extends Model
{
    use HasFactory;

     // Disable timestamps
     public $timestamps = false;

    protected $table = 'nas';

    protected $fillable = [
        'nasname',
        'shortname',
        'secret',
        'user_id'
    ];
}
