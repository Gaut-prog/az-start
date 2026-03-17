<?php

namespace App\Models\Immobilizations;

use App\Models\Immobilization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImmobilizationCategory extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'compta_actif_categories';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'Types',
        'Label',
        'Description',
        'name',
    ];

    public function immobilizations() : HasMany
    {
        return $this->hasMany(Immobilization::class, 'Categories', 'id');
    }

}