<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnderBanner extends Model
{
    use HasFactory;

    protected $table = 'web_under_banners';
    protected $connection = 'mysql2';

    protected $primaryKey = 'id';

    protected $fillable = [
        'picture',
        'title',
        'text',
        'fr_title',
        'fr_text',
        'products_services_description',
        'fr_products_services_description',
        'background_picture',
        'background_texte',
        'background_fr_texte',
        'contact_picture',
        'about_picture',
        'gallery_picture',
    ];

    protected $hidden = [
        'picture',
        'contact_picture',
        'about_picture',
        'gallery_picture',
        'background_picture'
    ];

    public $timestamps = false;

    public function getTTitleAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->fr_title : $this->title;
    }

    public function getTTextAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->fr_text : $this->text;
    }

    public function getTProductsServicesDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->fr_products_services_description : $this->products_services_description;
    }

    public function getTBackgroundTexteAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->background_fr_texte : $this->background_texte;
    }

    public function getFPictureAttribute(): string
    {
        return $this->picture ? 'data:image/jpeg;base64,'.base64_encode($this->picture) : '';
    }

    public function getFBackgroundPictureAttribute(): string
    {
        return $this->background_picture ? 'data:image/jpeg;base64,'.base64_encode($this->background_picture) : '';
    }

    public function getFContactPictureAttribute(): string
    {
        return $this->contact_picture ? 'data:image/jpeg;base64,'.base64_encode($this->contact_picture) : '';
    }

    public function getFAboutPictureAttribute(): string
    {
        return $this->about_picture ? 'data:image/jpeg;base64,'.base64_encode($this->about_picture) : '';
    }

    public function getFGalleryPictureAttribute(): string
    {
        return $this->gallery_picture ? 'data:image/jpeg;base64,'.base64_encode($this->gallery_picture) : '';
    }
}