<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleType extends Model
{
    use HasFactory;

    protected $table = 'article_type';
    protected $primaryKey = 'id_article_type';
    protected $connection = 'mysql2';


    public $timestamps = false;

    // Colonnes modifiables
    protected $fillable = [
        'Label',
        'en_Label',
        'Note',
    ];

    /**
     * Retourne le label selon la langue de l'application
     */
    public function getTLabelAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->Label : $this->en_Label;
    }
}