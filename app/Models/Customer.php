<?php

namespace App\Models;

use App\Action\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $primaryKey = 'Customers_Numbers';
    protected $table = 'customers';
    protected $connection = 'mysql';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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
        'Website',
        'Postal_Code',
        'Categories',
        'type',
        'Notes',
        'Appt',
        'Description',
        'Picture',
        'User',
        'added_from',
        'Brought_by',
        'Status',
        'created_date',
        'prefered_currency',
        'code_language'
    ];
    protected $casts = [
        'created_date' => 'datetime',
    ];
    // protected $hidden = [
    //     'Picture',
    //     'cover_picture',
    //     'signature',
    //     'cachet',
    //     'id_doc',
    // ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'customer_number', 'Customers_Numbers');
    }

    public function getIsCompanyAttribute()
    {
        return $this->type == 'webcompany';
    }
    
    public function getNameAttribute()
    {
        return $this->LastName . ' '. $this->Names;
    }

    public function getCodeIdAttribute() : string
    {
        return Helpers::codeId($this->Customers_Numbers);
    }
    
    public function getLangAttribute() : ?string
    {
        if(!Session::has('user_language')){
            $language = DB::table('languages')->where('code_language', $this->code_language)->first(['name', 'fr_name']);
            Session::put('user_language', $language);
        }else{
            $language = Session::get('user_language');
        }
        if($language)
            return app()->getLocale() == 'fr' ? $language->fr_name : $language->name ;
        
        return null;
    }
    
    public function getCurrencyAttribute() : array
    {
        if(!Session::has('user_currency')){
            $currency = DB::table('currency_rate')->where('id_currency', $this->prefered_currency)->first();
            Session::put('user_currency', $currency);
        }else{
            $currency = Session::get('user_currency');
        }
        return [
                    'id' => $currency->id_currency ?? null,
                    'name' => $currency->name ?? null,
                    'sign' => $currency->sign ?? null,
                    'value_to_usd' => $currency->value_to_usd ?? null,
                    'value_to_xof' => $currency->value_to_xof ?? null,
                ];
    }
    
     public function category() : BelongsTo  
    {
        return $this->belongsTo(CustomerCategory::class, 'Categories', 'Category_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Customer $customer) {
            $customer->added_from = 'az-manager';
            $customer->created_date = date('Y-m-d H:i:s');
            $customer->User = auth()->user()->customer_number ?? 'WebUser';
        });
    }
}