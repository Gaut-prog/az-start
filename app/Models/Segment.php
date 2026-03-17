<?php

namespace App\Models;

use App\Enums\CompanySize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Segment extends Model
{
    protected $primaryKey = 'id_segment';
    protected $table = 'commercials_segments';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'segment_name',
        'company_size',
        'employee',
        'description_potential_needs',
        'items_potential_needed',
        'activity_sector',
    ];
    
    protected $casts = [
        'company_size' => CompanySize::class,
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'employee', 'Customers_Numbers');
    }
    
    public function branch(): BelongsTo
    {
        return $this->belongsTo(CompanyCustomerCategory::class, 'activity_sector', 'Category_id');
    }
    
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'items_potential_needed', 'Items_Numbers');
    }

    protected static function booted(): void
    {
        static::creating(function (Segment $segment) {
            $segment->employee = auth()->user()->customer_number ?? 'WebUser';
        });
    }
}