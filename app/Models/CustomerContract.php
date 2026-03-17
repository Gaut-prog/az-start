<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CustomerContract extends Model
{
    protected $primaryKey = 'id_contract';
    protected $table = 'customers_contracts';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'article_title',
        'en_article_title',
        'article_description',
        'en_description',
        'customer_activity_sector',
        'contract_creation_date',
        'contract_last_update',
        'contract_created_by',
        'contract_updated_by',
    ];

    
    public function getTitleAttribute() : string
    {
        return app()->getLocale() == 'fr' ? $this->article_title : $this->en_article_title;
    }

    public function getDescriptionAttribute() : string
    {
        return app()->getLocale() == 'fr' ? $this->article_description : $this->en_description;
    }
    
    public function author() : BelongsTo
    {
        return $this->belongsTo(Customer::class, 'contract_created_by', 'Customers_Numbers');
    }
    
    public function editor() : BelongsTo
    {
        return $this->belongsTo(Customer::class, 'contract_last_update', 'Customers_Numbers');
    }
    
    public function items() : BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'customers_contract_item_group_sale', 'contract_id', 'item_number', 'id_contract', 'Items_Numbers');
    }
    
    public function sales() : BelongsToMany
    {
        return $this->belongsToMany(Sale::class, 'customers_contract_item_group_sale', 'contract_id', 'group_sales', 'id_contract', 'group_sales');
    }
    
    protected static function booted(): void
    {
        static::creating(function (CustomerContract $contract) {
            $contract->contract_creation_date = date('Y-m-d H:i:s');
             $contract->contract_last_update = date('Y-m-d H:i:s');
            $contract->contract_created_by = auth()->user()->customer_number ?? 'WebUser';
        });
        
        static::updating(function (CustomerContract $contract) {
            $contract->contract_last_update = date('Y-m-d H:i:s');
            $contract->contract_updated_by = auth()->user()->customer_number ?? 'WebUser';
        });
    }
}