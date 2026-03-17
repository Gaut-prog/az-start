<?php

namespace App\Models;

use App\Models\Immobilizations\ImmobilizationValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FiscalYear extends Model
{
    protected $primaryKey = 'id_period';
    protected $table = 'compta_fical_period';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'years',
        'starting_date',
        'ending_date',
        'status',
        'note',
    ];

    protected $casts = [
        'starting_date' => 'date',
        'ending_date' => 'date',
    ];

    public function immobilization() : BelongsTo
    {
        return $this->belongsTo(Immobilization::class, 'actif', 'id');
    }
    
    public function values() : HasMany
    {
        return $this->hasMany(ImmobilizationValue::class, 'fiscal_year', 'id_period');
    }

}