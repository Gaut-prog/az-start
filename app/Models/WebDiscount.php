<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebDiscount extends Model
{
    protected $primaryKey = 'discount_id';
    protected $table = 'web_discount';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'customer',
        'date',
        'code',
        'amount',
        'status',
        'description',
        'User',
    ];
    protected $casts = [
        'date' => 'datetime',
    ];

    public function client() : BelongsTo
    {
        return $this->belongsTo(CompanyCustomer::class, 'customer', 'Customers_Numbers');
    }

    protected static function booted(): void
    {
        static::creating(function (WebDiscount $discount) {
            $discount->status = 2;
            $discount->date = date('Y-m-d H:i:s');
            $discount->User = auth()->user()->customer_number ?? 'WebUser';
        });
    }
}