<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyCustomerGroupCategory extends Model
{
    protected  $table = 'customer_group_category';
    protected $primaryKey = 'number';
    protected $connection = 'mysql2';
    protected $fillable = [
        'name',
        'fr_name',
        'description',
        'fr_description',
        'banner',
    ];
    protected $hidden = [
        'banner'
    ];
    public $timestamps = false;

    public function categories() : HasMany  
    {
        return $this->HasMany(CompanyCustomerCategory::class, 'group_category', 'number');
    }

    public function getTNameAttribute()
    {
        return App::getLocale() == 'fr' ? $this->fr_name : $this->name;
    }
    
    public function getTDescriptionAttribute()
    {
        return App::getLocale() == 'fr' ? $this->fr_description : $this->description;
    }
    
    public function getPictureAttribute()
    {
        return $this->banner ? 'data:image/jpeg;base64,'.base64_encode($this->banner) : '';
    }
}