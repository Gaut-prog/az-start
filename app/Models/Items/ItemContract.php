<?php

namespace App\Models\Items;

use App\Models\CompanyCustomer;
use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemContract extends Model
{
    protected $primaryKey = 'contract_id';
    protected $table = 'items_contracts';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'generated_at',
        'signed_date',
        'item',
        'customer',
        'contract',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'signed_date' => 'datetime',
    ];

    public function linkedItem() : BelongsTo
    {
        return $this->belongsTo(Item::class, 'item', 'Items_Numbers');
    }
    
    public function linkedCustomer() : BelongsTo
    {
        return $this->belongsTo(CompanyCustomer::class, 'customer', 'Customers_Numbers');
    }
}