<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $primaryKey = 'employees_Number';
    protected $table = 'employees';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'az_id',
        'cashier',
        'FirstName',
        'LastName',
        'DateBirth',
        'Adress',
        'city',
        'province',
        'country',
        'emergency_contact',
        'emergency_contact_phone',
        'emergency_contact_email',
        'Phones',
        'Email',
        'NAS',
        'CV',
        'CV_Names',
        'Position',
        'TypeEmplyement',
        'weekly_working_hours',
        'Status',
        'StartDate',
        'EndDate',
        'BaseSalary',
        'HourlyRate',
        'PaymentPeriod',
        'Descriptiom',
        'Profils',
        'LegalDocuments',
        'LegalDoc_name',
        'manager',
        'contract',
        'created_date',
        'contract_generated_at',
    ];
    
    protected $casts = [
        'DateBirth' => 'datetime',
        'StartDate' => 'datetime',
        'EndDate' => 'datetime',
        'created_date' => 'datetime',
        'contract_generated_at' => 'datetime',
    ];

    public function reservations() : HasMany
    {
        return $this->hasMany(Employee::class, 'employee', 'employees_Number');
    }

    public function getFullNameAttribute(): string 
    {  
        return strtoupper($this->LastName). " {$this->FirstName}";
    }

    public function scopeActive($query){
        return $query->where('Status', 'Actif');
    }

    protected static function booted(): void
    {
        static::creating(function (Employee $employee) {
            $employee->created_date = date('Y-m-d H:i:s');
        });
    }
}