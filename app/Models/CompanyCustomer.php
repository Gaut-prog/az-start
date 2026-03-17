<?php

namespace App\Models;

use App\Action\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyCustomer extends Model
{
    protected $primaryKey = 'Customers_Numbers';
    protected $table = 'customers';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'az_id',
        'Names',
        'LastName',
        'Phones',
        'E-mails',
        'Country',
        'Province',
        'City',
        'Adresses',
        'Postal_Code',
        'Categories',
        'type',
        'Appt',
        'Description',
        'Picture',
        'User',
        'added_from',
        'Status',
        'created_date',
        'id_doc',
        'Website',
    ];
    protected $casts = [
        'created_date' => 'datetime',
    ];
    protected $hidden = [
        'Picture',
        'id_doc',
    ];

    public function category() : BelongsTo
    {
        return $this->belongsTo(CompanyCustomerCategory::class, 'Categories', 'Category_id');
    }
    
    public function rcountry() : BelongsTo
    {
        return $this->belongsTo(Country::class, 'Country', 'code');
    }
    
    public function rprovince() : BelongsTo
    {
        return $this->belongsTo(Province::class, 'Province', 'code_province');
    }
    
    public function rcity() : BelongsTo
    {
        return $this->belongsTo(City::class, 'City', 'code_city');
    }
    
    public function getNameAttribute() : string
    {
        return $this->Names . ' ' . $this->LastName;
    }
    
    public function getCodeIdAttribute() : string
    {
        return $this->az_id ? Helpers::codeId($this->az_id) : '';
    }

    public function scopeActive($query)
    {
        return $query->where('Status', 1);
    }
    
    public function scopeProspect($query)
    {
        return $query->whereRaw('Customers_Numbers NOT IN (SELECT DISTINCT Customers_Numbers FROM sales WHERE Amount_Paid != 0)');
    }

    protected static function booted(): void
    {
        static::creating(function (CompanyCustomer $customer) {
            $customer->added_from = 'az-manager';
            $customer->created_date = date('Y-m-d H:i:s');
            $customer->User = auth()->user()->customer_number ?? 'WebUser';
        });
    }
}