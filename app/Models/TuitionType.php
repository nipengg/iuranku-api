<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TuitionType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tuition_type";
    protected $fillable = [
        'tuition_name'
    ];

    public function tuition(): HasMany
    {
        return $this->hasMany(Tuition::class, 'type_tuition_id', 'id');
    }
}
