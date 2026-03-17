<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    protected $primaryKey = 'Providers_Numbers';
    protected $table = 'providers';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'created_date',
        'provider_type',
        'Categories',
        'Descriptions',
        'Names',
        'Phones',
        'Country',
        'Province',
        'City',
        'E-mails',
        'website_link',
        'photo',
        'Adresses',
        'unit_number',
        'Postal_Code',
        'Status',
        'Notes',
    ];

    protected $casts = [
        'created_date' => 'datetime',
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'Providers_Numbers', 'Providers_Numbers');
    }

    public function branch() : BelongsTo
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

    public function getLogoAttribute(): string
    {
        return $this->photo ? "data:image/jpeg;base64,". base64_encode($this->photo) : "";
    }

    protected static function booted(): void
    {
        static::creating(function (Provider $provider) {
            $provider->created_date = date('Y-m-d H:i:s');
            $provider->Status = 1;
        });
    }
}