<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaultItem extends Model
{
    protected $fillable = ['file', 'mime'];
}