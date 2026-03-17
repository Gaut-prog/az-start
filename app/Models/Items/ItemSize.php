<?php

namespace App\Models\Items;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemSize extends Model
{
    protected $primaryKey = 'size_id';
    protected $table = 'items_sizes';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'size',
        'note',
        'price',
    ];

    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'Items_Numbers');
    }

    public function getFormatedPriceAttribute() : string
    {
        return number_format($this->price, 2, '.', ' ');
    }
}