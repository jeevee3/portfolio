<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaultLog extends Model
{
    protected $fillable = ['ip', 'agent', 'success'];

    protected $casts = [
        'success' => 'boolean',
    ];
}