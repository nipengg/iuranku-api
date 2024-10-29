<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestTuition extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'request_tuition';
    protected $fillable = [
        'member_id',
        'type_tuition_id',
        'nominal',
        'file',
        'status',
        'start_date',
        'end_date',
        'remark',
    ];
}
