<?php

namespace App\Models;

use App\Models\Items\ItemMeasurement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    protected $primaryKey = 'id_ingredients';
    protected $table = 'ingredients';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'fr_name',
        'description',
        'fr_description',
        'needed_quantity_for_one_item',
        'price',
        'unit_of_measurement',
        'picture',
        'related_link',
        'related_document',
    ];
    
    protected $hidden = [
        'picture',
        'related_document'
    ];

    public function getTNameAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_name : $this->name;
    }
    
    public function getTDescriptionAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_description : $this->description;
    }

    public function getFPictureAttribute() : string
    {
        return $this->picture ? 'data:image/jpeg;base64,'.base64_encode($this->picture) : '';
    }

    public function getFormatedPriceAttribute() : string
    {
        return number_format($this->price, 2, '.', ' ');
    }

    public function measure(): BelongsTo
    {
        return $this->belongsTo(ItemMeasurement::class, 'unit_of_measurement', 'id_measurement');
    }
    
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'ingredient_item', 'ingredient_id', 'item_id', 'id_ingredients', 'Items_Numbers')
                    ->withPivot(['needed_quantity']);
    }

    public function stocks() : HasMany
    {
        return $this->hasMany(IngredientStock::class, 'id_ingredients', 'id_ingredients');
    }
}