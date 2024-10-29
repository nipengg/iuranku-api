<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupTuitionSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'group_tuition_setting';
    protected $fillable = [
        'group_id',
        'type_tuition_id',
        'tuition_value',
        'tuition_period',
    ];
}
