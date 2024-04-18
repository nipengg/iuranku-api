<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_type_name',
    ];

    public function group_member()
    {
        return $this->hasMany(GroupMember::class);
    }
}
