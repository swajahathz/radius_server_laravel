<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RS_service_shuduler extends Model
{
    use HasFactory;
    protected $table = 'services_shuduler';
    public $timestamps = false;

    protected $fillable = [
        'status'
    ];
}
