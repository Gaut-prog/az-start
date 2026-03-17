<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemCategoryGroup extends Model
{
    protected $primaryKey = 'Group_id';
    protected $table = 'item_group_category';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'Names',
        'fr_Name',
        'Description',
        'fr_Description',
        'Pictures',
        'icon',
    ];
    protected $hidden = [
        'Pictures'
    ];

    public function getTNameAttribute() : string
    {
        return app()->getLocale() == 'fr' ? $this->fr_Name : $this->Names;
    }

    public function categories() : HasMany
    {
        return $this->hasMany(ItemCategory::class, 'group_category', 'Group_id');
    }
}