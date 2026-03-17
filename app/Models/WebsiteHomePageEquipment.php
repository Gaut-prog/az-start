<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteHomePageEquipment extends Model
{
    protected $table = 'website_homepage_equipment';

    public $timestamps = false;
    protected $connection = 'mysql2';

    protected $fillable = [
        'image',
        'title',
        'fr_title',
        'text',
        'fr_text',
    ];

    protected $hidden = [
        'image'
    ];

    public function getFImageAttribute()
    {
        return $this->image ? 'data:image/jpeg;base64,'.base64_encode($this->image) : null;
    }

    public function getTTitleAttribute()
    {
        return app()->getLocale() == 'fr' ? $this->fr_title : $this->title;
    }

    public function getTTextAttribute()
    {
        return app()->getLocale() == 'fr' ? $this->fr_text : $this->text;
    }
}