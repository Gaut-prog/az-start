<?php

namespace App\Models;

use App\Models\Immobilizations\ImmobilizationCategory;
use App\Models\Immobilizations\ImmobilizationHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Immobilization extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'compta_actif';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'acquisition_date',
        'name',
        'serial_number',
        'residual_value',
        'Note',
        'Categories',
        'status',
        'price_of_sale',
        'date_of_sales',
        'customer_number',
        'cashier',
    ];
    
    protected $casts = [
        'acquisition_date' => 'date',
        'date_of_sales' => 'datetime',
    ];
    
    public function getFormatedPriceAttribute() : string
    {
        return number_format($this->price_of_sale, 2, '.', ' ');
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(ImmobilizationCategory::class, 'Categories', 'id');
    }
    
    public function customer() : BelongsTo
    {
        return $this->belongsTo(CompanyCustomer::class, 'customer_number', 'Customers_Numbers');
    }
    
    public function bankaccount() : BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'cashier', 'id_account');
    }

    public function usages() : HasMany
    {
        return $this->hasMany(ImmobilizationHistory::class, 'id_actif', 'id');
    }
    
    public function currentUsage()
    {
        return $this->hasOne(ImmobilizationHistory::class, 'id_actif', 'id')->orderByDesc('endind_date');
    }

}