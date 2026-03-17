<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerCategory extends Model
{
    protected  $table = 'customer_category';
    protected $primaryKey = 'Category_id';
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
        return $this->HasMany(Customer::class, 'Categories', 'Category_id');
    }

    public function getPictureAttribute() : string
    {
        return $this->Pictures ? 'data:image/jpeg;base64,'.base64_encode($this->Pictures) : '';
    }

    public function getNameAttribute()
    {
        return App::getLocale() == 'fr' ? $this->fr_Name : $this->Names;
    }
}