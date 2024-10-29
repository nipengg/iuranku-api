<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tuition extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tuition";
    protected $fillable = [
        'request_tuition_id',
        'nominal',
        'nominal_percentage',
        'period',
    ];
}
