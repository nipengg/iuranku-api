<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'group_description',
        'group_address',
        'user_in'
    ];

    public function group_member()
    {
        return $this->hasMany(GroupMember::class, 'group_id', 'id');
    }
}
