<?php

namespace App\Models\Immobilizations;

use App\Enums\ImmobilizationUsage;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Immobilization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImmobilizationHistory extends Model
{
    protected $primaryKey = 'id_usage';
    protected $table = 'compta_actif_usage';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'id_actif',
        'statuts',
        'starting_date',
        'endind_date',
        'used_by',
        'assigned_date',
        'assigned_by',
        'note',
    ];

    protected $casts = [
        'starting_date' => 'datetime',
        'endind_date' => 'datetime',
        'assigned_date' => 'datetime',
        'statuts' => ImmobilizationUsage::class,
    ];

    public function immobilization() : BelongsTo
    {
        return $this->belongsTo(Immobilization::class, 'id_actif', 'id');
    }
    
    public function employee() : BelongsTo
    {
        return $this->belongsTo(Employee::class, 'used_by', 'employees_Number');
    }
    
    public function assignator() : BelongsTo
    {
        return $this->belongsTo(Customer::class, 'assigned_by', 'Customers_Numbers');
    }

    protected static function booted(): void
    {
        static::creating(function (ImmobilizationHistory $usage) {
            $usage->assigned_date = now();
            $usage->assigned_by = auth()->user()->customer_number ?? 'WebUser';
        });
    }

}