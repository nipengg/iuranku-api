<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function typeTuition(): BelongsTo
    {
        return $this->belongsTo(TuitionType::class, 'type_tuition_id', 'id');
    }
}
