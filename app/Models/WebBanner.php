<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebBanner extends Model
{
    use HasFactory;

    protected $table = 'web_banners';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'picture',
        'text1',
        'text2',
        'text3',
        'fr_text1',
        'fr_text2',
        'fr_text3',
        'page_id',
    ];
    
    protected $hidden = [
        'picture'
    ];

    // Relation avec WebPage
    public function page()
    {
        return $this->belongsTo(WebPage::class, 'page_id', 'id');
    }

    public function getTText1Attribute() : ?string 
    {
        return app()->getLocale() == 'fr' ? $this->fr_text1 : $this->text1;
    }
    
    public function getTText2Attribute() : ?string 
    {
        return app()->getLocale() == 'fr' ? $this->fr_text2 : $this->text2;
    }
    
    public function getTText3Attribute() : ?string 
    {
        return app()->getLocale() == 'fr' ? $this->fr_text3 : $this->text3;
    }
    
    public function getFPictureAttribute() : string 
    {
        return $this->picture ? 'data:image/jpeg;base64,'.base64_encode($this->picture) : '';
    }
}