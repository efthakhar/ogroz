<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = [
        'name',
        'number',
        'accountable_type',
        'accountable_id',
        'account_group_id',
        'opening_debit',
        'opening_credit',
        'opening_base_currency_id',
        'opening_currency_id',
        'opening_exchange_rate',
        'active',
    ];


    public function accountGroup(): BelongsTo
    {
        return $this->belongsTo(AccountGroup::class);
    }
}
