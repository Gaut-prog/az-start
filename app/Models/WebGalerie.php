<?php

namespace App\Models;

use App\Enums\GalleryFileType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebGalerie extends Model
{
    use HasFactory;

    protected $table = 'web_galeries';
    protected $primaryKey = 'id';
    protected $connection = 'mysql2';

    public $timestamps = false;

    // Colonnes modifiables
    protected $fillable = [
        'type',
        'title',
        'fr_title',
        'file',
        'status',
    ];

    protected $hidden = ['file'];
    protected $casts = [
        'type' => GalleryFileType::class
    ];

    public function getTTitleAttribute(): string
    {
        return app()->getLocale() === 'fr' ? $this->fr_title : $this->title;
    }

    
    public function getFFileAttribute(): ?string
    {
        return $this->type->value == 'photo'
            ? 'data:image/jpeg;base64,' . base64_encode($this->file)
            : 'data:video/mp4;base64,' . base64_encode($this->file);
    }

    protected static function booted(): void
    {
        static::creating(function (WebGalerie $gallery) {
            $gallery->status = 1;
        });
    }

}