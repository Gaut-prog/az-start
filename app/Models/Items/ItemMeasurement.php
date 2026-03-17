<?php

namespace App\Models\Items;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemMeasurement extends Model
{
    protected $primaryKey = 'id_measurement';
    protected $table = 'items_measurement';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'label',
        'fr_label',
        'description',
    ];

    public function getNameAttribute() : string
    {
        return app()->getLocale() == 'fr' ? $this->fr_label : $this->label;
    }

    public function items() : HasMany
    {
        return $this->hasMany(Item::class, 'unit_of_measurement', 'id_measurement');
    }
}