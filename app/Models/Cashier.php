<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
    protected $primaryKey = 'Number';
    protected $table = 'cashier';
    protected $connection = 'mysql2';
    public $timestamps = false;
    
    protected $fillable = [
        'Name',
        'Location',
        'address_latitude',
        'address_longitude',
        'IsWarehouse',
        'Description',
    ];

    public function scopeWarehouse($query)
    {
        return $query->where('IsWarehouse', 1);
    }
}