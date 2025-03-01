<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    protected $fillable = [
        'name',
        'type',
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

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
