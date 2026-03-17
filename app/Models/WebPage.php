<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebPage extends Model
{
    use HasFactory;

    protected $table = 'web_pages';

    protected $primaryKey = 'id';
    protected $connection = 'mysql2';
    public $timestamps = false;

    // Colonnes modifiables
    protected $fillable = [
        'name',
        'fr_name',
    ];

    public function getTNameAttribute() : string 
    {
        return app()->getLocale() == 'fr' ? $this->fr_name : $this->name;
    }

    public function banners()
    {
        return $this->belongsTo(WebBanner::class, 'page_id', 'id');
    }
}