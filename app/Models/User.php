<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'gender',
        'address',
        'phone',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function group_member(): HasMany
    {
        return $this->hasMany(GroupMember::class, 'user_id', 'id');
    }

    public function group_news(): HasMany
    {
        return $this->hasMany(GroupNews::class, 'author_id', 'id');
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'author_id', 'id');
    }

    public function group_application(): HasMany
    {
        return $this->hasMany(GroupApplication::class, 'user_id', 'id');
    }

    public function groups(): HasManyThrough
    {
        return $this->hasManyThrough(Group::class, GroupMember::class, 'user_id', 'id', 'id', 'group_id');
    }

    public function delete()
    {
        if ($this->group_member()->exists() || $this->group_news()->exists() || $this->news()->exists()) {
            throw new Exception('Cannot delete this user because it has related records.');
        }
        
        return parent::delete();
    }
}
