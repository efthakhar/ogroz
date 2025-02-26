<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    protected $fillable = [
        'name',
        'active',
        'type',
        'level',
        'parent_account_group_id'
    ];

    public function parent()
    {
        return $this->belongsTo(AccountGroup::class, 'parent_account_group_id');
    }

    public function childrens()
    {
        return $this->hasMany(AccountGroup::class, 'parent_account_group_id');
    }

    public function childrensRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
