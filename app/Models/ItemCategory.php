<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ItemCategory extends Model
{
    protected $primaryKey = 'Category_id';
    protected $table = 'item_category';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'group_category',
        'Names',
        'fr_Name',
        'Descriptions',
        'fr_description',
        'Pictures',
        'icon',
        'certification',
        'starting_date',
        'ending_date',
        'prices',
    ];
    protected $casts = [
        'starting_date' => 'datetime',
        'ending_date' => 'datetime',
    ];
    protected $hidden = [
        'Pictures'
    ];

    public function getTNameAttribute() : string
    {
        return app()->getLocale() == 'fr' ? $this->fr_Name : $this->Names;
    }

    public function getTDescriptionAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_description : $this->Descriptions;
    }
    
    public function getPictureAttribute() : string
    {
        return $this->Pictures ? 'data:image/jpeg;base64,'.base64_encode($this->Pictures) : '';
    }
    
    public function getFormatedPriceAttribute() : string
    {
        return number_format($this->prices, 2, '.', ' ');
    }

    public function group() : BelongsTo
    {
        return $this->belongsTo(ItemCategoryGroup::class, 'group_category', 'Group_id');
    }

    public function items() : HasMany
    {
        return $this->hasMany(Item::class, 'Categories', 'Category_id');
    }

    public function menues(): BelongsToMany
    {
        return $this->belongsToMany(DailyMenu::class, 'daily_menu_item_category', 'item_category_id', 'daily_menu_id', 'Category_id', 'id');
    }
}