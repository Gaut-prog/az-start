<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeObjectif extends Model
{
    protected $primaryKey = 'id_objectif';
    protected $table = 'employees_objectifs';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'employee_az_id',
        'objectif_year',
        'objectif_period',
        'period',
        'sales_objectif',
        'sales_quantity_objectif',
        'reservation_objectif',
        'subscription_objectif',
        'expenses_objectif',
        'creation_date',
        'created_by_az_id',
        'note',
    ];
    
    protected $casts = [
        'creation_date' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'created_by_az_id', 'Customers_Numbers');
    }
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_az_id', 'az_id');
    }

    protected static function booted(): void
    {
        static::creating(function (EmployeeObjectif $employeeObjectif) {
            $employeeObjectif->created_by_az_id = auth()->user()->customer_number ?? 'WebUser';
            $employeeObjectif->creation_date = now();
        });
    }
}