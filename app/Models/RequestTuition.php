<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestTuition extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'request_tuition';
    protected $fillable = [
        'member_id',
        'nominal',
        'file',
        'status',
        'remark',
    ];

    
    public function member(): BelongsTo
    {
        return $this->belongsTo(GroupMember::class, 'member_id', 'id');
    }

    public function tuition(): HasMany
    {
        return $this->hasMany(Tuition::class, 'request_tuition_id', 'id');
    }
}
