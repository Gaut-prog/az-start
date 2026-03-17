<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    protected $table = 'currency_rate';

    protected $primaryKey = 'id_currency';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'sign',
        'value_to_usd',
        'value_to_xof',
        'note',
    ];
}