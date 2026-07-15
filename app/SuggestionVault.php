<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuggestionVault extends Model
{
    protected $table = 'suggestion_vault';
    protected $primaryKey = 'id';
    protected $fillable = [
        'suggestion',
        'type',
    ];
}
