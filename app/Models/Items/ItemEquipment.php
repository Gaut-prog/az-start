<?php

namespace App\Models\Items;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ItemEquipment extends Model
{
    protected $primaryKey = 'equipment_id';
    protected $table = 'equipments';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'equipment_icon',
        'equipment_title',
        'equipment_fr_title',
    ];
    
    protected $hidden = [
        'equipment_icon'
    ];

    public function getTitleAttribute() : string
    {
        return app()->getLocale() == 'fr' ? $this->equipment_fr_title : $this->equipment_title;
    } 

    public function getIconAttribute() : string
    {
        return $this->equipment_icon ? 'data:image/jpeg;base64,'.base64_encode($this->equipment_icon) : '';
    } 
    
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'items_equipments', 'equipment_id', 'item_id', 'equipment_id', 'Items_Numbers');
    }
}