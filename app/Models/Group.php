<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function group_member(): HasMany
    {
        return $this->hasMany(GroupMember::class, 'group_id', 'id');
    }

    public function group_application(): HasMany
    {
        return $this->hasMany(GroupApplication::class, 'group_id', 'id');
    }

    public function group_news(): HasMany
    {
        return $this->hasMany(GroupNews::class, 'group_id', 'id');
    }

    public function group_tuition_setting(): HasMany
    {
        return $this->hasMany(GroupTuitionSetting::class, 'group_id', 'id');
    }

    public function delete()
    {
        if ($this->group_application()->exists() || $this->group_news()->exists() || $this->group_tuition_setting()->exists() || $this->group_member()->count() > 1) {
            throw new Exception('Cannot delete this group because it has related records.');
        }

        $this->group_member()->delete();

        return parent::delete();
    }
}
