<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngredientStock extends Model
{
    protected $primaryKey = 'id_transaction';
    protected $table = 'items_ingredients_transactions';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'id_ingredients',
        'transaction_type',
        'transaction_quantity',
        'note',
        'date',
        'warehouse',
        'user',
    ];
    
    protected $casts = [
        'date' => 'datetime'
    ];

    public function ingredient() : BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'id_ingredients', 'id_ingredients');
    }
    
    public function cashier() : BelongsTo
    {
        return $this->belongsTo(Cashier::class, 'warehouse', 'Number');
    }
}