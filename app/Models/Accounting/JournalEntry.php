<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends Model
{
    protected $fillable = [
        'journalable_type',
        'journalable_id',
        'date',
        'remarks',
        'base_currency_id',
        'currency_id',
        'exchange_rate',
    ];

    public function accountJournalEntries(): HasMany
    {
        return $this->hasMany(AccountJournalEntry::class);
    }
}
