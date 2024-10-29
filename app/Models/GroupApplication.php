<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'group_application';
    protected $fillable = [
        'user_id',
        'group_id',
        'status',
    ];
}
