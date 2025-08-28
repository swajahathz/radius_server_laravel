<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class RS_radgroupreply extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'radgroupreply';

    protected $fillable = [
        'groupname',
        'attribute',
        'op',
        'value',
        'group_id',
        'user_id'
    ];
}
