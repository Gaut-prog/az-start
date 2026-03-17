<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DailyMenu extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'daily_menus';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'day_number',
        'day_name',
        'day_fr_name',
        'description',
        'fr_description',
    ];

    public function getDayAttribute() : string
    {
        return app()->getLocale() == 'fr' ? $this->day_fr_name : $this->day_name;
    }
    
    public function getTDescriptionAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_description : $this->description;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ItemCategory::class, 'daily_menu_item_category', 'daily_menu_id', 'item_category_id', 'id', 'Category_id');
    }
}