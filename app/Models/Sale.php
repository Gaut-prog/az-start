<?php

namespace App\Models;

use App\Models\Views\SaleInvoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sale extends Model
{
    protected $primaryKey = 'Sales_Numbers';
    protected $table = 'sales';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'Items_Numbers',
        'group_sales',
        'Quantities',
        'Unique Prices',
        'discount_id',
        'Amount_Paid',
        'Discount',
        'cashier',
        'served_by',
        'commision_amount',
        'expected_service_date',
        'delivered_date',
        'delivered',
        'Taxe1',
        'Taxe2',
        'Taxe3',
        'Dates',
        'payment_deadline',
        'Customers_Numbers',
        'sales_type',
        'linked_project',
        'Descriptions',
        'Bills',
        'Bills_Names',
        'bl_file',
        'bl_file_name',
        'User',
        'repatriated',
        'seat_id',
        'types',
    ];
    protected $casts = [
        'expected_service_date' => 'datetime',
        'delivered_date' => 'datetime',
        'Dates' => 'datetime',
        'payment_deadline' => 'datetime',
    ];

    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class, 'Items_Numbers', 'Items_Numbers');
    }
    
    public function customer() : BelongsTo
    {
        return $this->belongsTo(CompanyCustomer::class, 'Customers_Numbers', 'Customers_Numbers');
    }

    public function contracts() : BelongsToMany
    {
        return $this->belongsToMany(CustomerContract::class, 'customers_contract_item_group_sale', 'group_sales', 'contract_id', 'group_sales', 'id_contract');
    }
    
    public function invoice()
    {
        return SaleInvoice::whereRaw('`No. Command` = ?', [$this->Sales_Numbers])->first();
    }
    
    public function item_sales()
    {
        return $this->where('group_sales', $this->group_sales)->get();
    }

    public function scopeNotRepatriated($query)
    {
        return $query->where('repatriated', 0);
    }
    
    public function scopeNotReserved($query)
    {
        return $query->whereRaw('sales.`Unique Prices` !=0 AND sales.group_sales IS NOT NULL AND sales.Sales_Numbers NOT IN (SELECT sales_invoice FROM reservation WHERE sales_invoice IS NOT NULL)');
    }
    public function scopeNotSubscribed($query)
    {
        return $query->whereRaw('sales.`Unique Prices` !=0 AND sales.group_sales IS NOT NULL 
            AND sales.Items_Numbers IN (SELECT Items_Numbers FROM items WHERE subscription_frequency IS NOT NULL)
            AND sales.Sales_Numbers NOT IN (SELECT sales_invoice FROM reservation WHERE sales_invoice IS NOT NULL)');
    }

    protected static function booted(): void
    {
        static::creating(function (Sale $sale) {
            $sale->User = auth()->user()->customer_number ?? 'WebUser';
        });
    }
}