<?php

namespace App\Models\Items;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemImage extends Model
{
    protected $primaryKey = 'ID';
    protected $table = 'items_images';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'Id_item',
        'Image',
        'color',
        'color_name',
        'color_fr_name',
    ];
    
    protected $hidden = [
        'Image'
    ];

    public function getTColorNameAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->color_fr_name : $this->color_name;
    }
    
    public function getFImageAttribute() : string
    {
        return $this->Image ? 'data:image/jpeg;base64,'.base64_encode($this->Image) : '';
    }

    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class, 'Id_item', 'Items_Numbers');
    }
}