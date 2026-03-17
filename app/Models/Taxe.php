<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxe extends Model
{
    protected $primaryKey = 'id_Taxes';
    protected $table = 'taxes';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'Name',
        'Value',
        'Description',
        'Dates',
    ];

    protected static function booted(): void
    {
        static::creating(function (Taxe $taxe) {
            $taxe->Dates = date('Y-m-d H:i:s');
        });
    }
}