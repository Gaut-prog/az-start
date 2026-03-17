<?php

namespace App\Models\Immobilizations;

use App\Models\FiscalYear;
use App\Models\Immobilization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImmobilizationValue extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'compta_actif_value';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'fiscal_year',
        'actif',
        'value',
        'note',
    ];

    public function immobilization() : BelongsTo
    {
        return $this->belongsTo(Immobilization::class, 'actif', 'id');
    }
    
    public function year() : BelongsTo
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year', 'id_period');
    }

}