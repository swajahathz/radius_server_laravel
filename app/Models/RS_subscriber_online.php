<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RS_subscriber_online extends Model
{
    use HasFactory;
    protected $table = 'radacct';
    public $timestamps = false;
    protected $primaryKey = 'radacctid';

    protected $fillable = [
        'srvch_id'
    ];
}
