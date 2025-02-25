<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = [
        'name',
        'active',
        'accountable_type',
        'accountable_id',
        'name',
        'number',
        'account_group_id',
        'opening_debit',
        'opening_credit',
        'opening_base_currency_id',
        'opening_currency_id',
        'opening_exchange_rate',
    ];


    public function accountGroup(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
