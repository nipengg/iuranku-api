<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tuition extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tuition";
    protected $fillable = [
        'request_tuition_id',
        'member_id',
        'type_tuition_id',
        'nominal',
        'period',
    ];

    public function requestTuition(): BelongsTo
    {
        return $this->belongsTo(RequestTuition::class, 'request_tuition_id', 'id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(GroupMember::class, 'member_id', 'id');
    }

    public function typeTuition(): BelongsTo
    {
        return $this->belongsTo(TuitionType::class, 'type_tuition_id', 'id');
    }
}
