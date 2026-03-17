<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyCustomerCategory extends Model
{
    protected  $table = 'customer_category';
    protected $primaryKey = 'Category_id';
    protected $connection = 'mysql2';
    protected $fillable = [
        'group_category',
        'Names',
        'fr_Name',
        'Descriptions',
        'fr_Descriptions',
        'Pictures',
    ];
    protected $hidden = [
        'Pictures'
    ];
    public $timestamps = false;

    public function customers() : HasMany  
    {
        return $this->HasMany(CompanyCustomer::class, 'Categories', 'Category_id');
    }

    public function group() : BelongsTo
    {
        return $this->belongsTo(CompanyCustomerGroupCategory::class, 'group_category', 'number');
    }

    public function getTNameAttribute()
    {
        return App::getLocale() == 'fr' ? $this->fr_Name : $this->Names;
    }
    
    public function getTDescriptionAttribute()
    {
        return App::getLocale() == 'fr' ? $this->fr_Descriptions : $this->Descriptions;
    }

    public function getPictureAttribute() :string
    {
        return $this->Pictures ? 'data:image/jpeg;base64,'.base64_encode($this->Pictures) : '';
    }
}