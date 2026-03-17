<?php

namespace App\Models;

use App\Enums\ExpenseCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $primaryKey = 'Orders_Numbers';
    protected $table = 'ordersservices';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'Providers_Numbers',
        'Item',
        'linked_project',
        'linked_asset',
        'linked_stock',
        'linked_item_ingredient',
        'linked_supply_orders',
        'Types',
        'Types_Charges',
        'Descriptions',
        'Quantities',
        'Unique Prices',
        'Amount_Paid',
        'cashier',
        'Dates',
        'expenses_types',
        'Notes',
        'Bills',
        'Bills_Names',
        'Taxe1',
        'Taxe1_Deductible',
        'Taxe2',
        'Taxe2_Deductible',
        'Taxe3',
        'Taxe3_Deductible',
        'reception_status',
        'User',
    ];
    
    protected $casts = [
        'Dates' => 'datetime',
        'expenses_types' => ExpenseCategory::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (Expense $expense) {
            $expense->User = auth()->user()->customer_number ?? 'WebUser';
            $expense->Dates = now();
        });
    }

    public function type() : BelongsTo
    {
        return $this->belongsTo(ExpenseType::class, 'Types', 'id_types');
    }
    
    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class, 'Item', 'Items_Numbers');
    }
    
    public function ingredient() : BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'linked_item_ingredient', 'id_ingredients');
    }
    
    public function project() : BelongsTo
    {
        return $this->belongsTo(Project::class, 'linked_project', 'id_project');
    }
    
    public function provider() : BelongsTo
    {
        return $this->belongsTo(Provider::class, 'Providers_Numbers', 'Providers_Numbers');
    }
    public function bankaccount() : BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'cashier', 'id_account');
    }
    
    public function author(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'User', 'Customers_Numbers');
    }
    
    public function getFactureAttribute(): string
    {
        $invoice = "";
        if($this->Bills)
            $invoice = 'href="'. 'data:octet/stream;base64,'. base64_encode($this->Bills). '"';
        if($this->Bills_Names)
            $invoice .= 'download="'. $this->Bills_Names . '"';
        
        return $invoice;
    }
}