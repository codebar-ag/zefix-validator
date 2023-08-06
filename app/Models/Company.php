<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $casts = [
        'zefix_completed_at' => 'datetime',
        'check_valid_name' => 'boolean',
        'check_valid_uid' => 'boolean',
        'check_account_number_duplicate' => 'boolean',
        'check_account_number_duplicate_count' => 'integer',
        'check_uid_duplicate' => 'boolean',
        'check_uid_duplicate_count' => 'integer',
        'check_completed_at' => 'datetime',
    ];
}
