<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_name',
        'group_description',
        'group_address',
        'user_in'
    ];

    public function toArray()
    {
        $array = parent::toArray();
        unset($array['laravel_through_key']);
        return $array;
    }

    public function group_member()
    {
        return $this->hasMany(GroupMember::class, 'group_id', 'id');
    }
}
