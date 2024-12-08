<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = ['group_id', 'user_id', 'member_type_id'];

    protected $fillable = [
        'user_id',
        'group_id',
        'member_type_id',
        'status',
        'join_date',
        'leave_date',
        'leave_note',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function member_type(): BelongsTo
    {
        return $this->belongsTo(MemberType::class, 'member_type_id', 'id');
    }
}
