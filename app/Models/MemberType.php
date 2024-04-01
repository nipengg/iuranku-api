<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberType extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_type_name',
    ];

    public function group_member()
    {
        return $this->hasMany(GroupMember::class);
    }
}
