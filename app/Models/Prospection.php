<?php

namespace App\Models;

use App\Enums\ApproachMethod;
use App\Enums\ProspectionStatus;
use App\Enums\ProspectionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prospection extends Model
{
    use HasFactory;

    protected $table = 'commercials_prospections';
    protected $primaryKey = 'id_prospection';
    public $timestamps = false;
    protected $connection = 'mysql2';

    protected $fillable = [
        'prospection_type',
        'approach_method',
        'prospection_status',
        'planned_date',
        'period_type',
        'period',
        'company_potential_need',
        'main_item_interested',
        'interest_details',
        'prospected_by_employee',
        'prospected_company',
        'prospection_note',
    ];

     protected $casts = [
        'planned_date' => 'datetime',
        'approach_method' => ApproachMethod::class,
        'prospection_type' => ProspectionType::class,
        'prospection_status' => ProspectionStatus::class,
    ];

    public function followups() : HasMany
    {
        return $this->hasMany(ProspectionFollowup::class, 'prospection_id', 'id_prospection');
    }

    public function employee() : BelongsTo
    {
        return $this->belongsTo(Customer::class, 'prospected_by_employee', 'Customers_Numbers');
    }
    
    public function company() : BelongsTo
    {
        return $this->belongsTo(CompanyCustomer::class, 'prospected_company', 'Customers_Numbers');
    }
    
    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class, 'main_item_interested', 'Items_Numbers');
    }

    protected static function booted(): void
    {
        static::creating(function (Prospection $prospection) {
            $prospection->prospected_by_employee = auth()->user()->customer_number ?? 'WebUser';
        });
    }

}