<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $hidden = ['group_id', 'user_id', 'member_type_id'];

    protected $fillable = [
        'user_id',
        'group_id',
        'member_type_id',
        'status',
        'join_date',
        'leave_date',
        'leave_type',
        'leave_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function member_type()
    {
        return $this->belongsTo(MemberType::class, 'member_type_id', 'id');
    }
}
