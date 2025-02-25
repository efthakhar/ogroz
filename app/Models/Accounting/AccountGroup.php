<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    protected $fillable = ['name', 'active', 'type', 'level', 'under']; 
}
