<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingDeliveryCost extends Model
{
    protected $primaryKey = 'id_shipping_deliver';
    protected $table = 'shipping_delivery';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'Country',
        'City',
        'Prices',
        'Commentaire',
    ];
    
    public function rcountry() : BelongsTo
    {
        return $this->belongsTo(Country::class, 'Country', 'code');
    }
    
    public function rcity() : BelongsTo
    {
        return $this->belongsTo(City::class, 'City', 'code_city');
    }
}