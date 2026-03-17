<?php

namespace App\Models;

use App\Enums\PositionStatus;
use App\Enums\ProspectionFollowupType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectionFollowup extends Model
{
    use HasFactory;

    protected $table = 'customers_followup';
    protected $primaryKey = 'id_followup';
    public $timestamps = false;
    protected $connection = 'mysql2';

    protected $fillable = [
        'prospection_id',
        'followup_by_employee',
        'followup_date',
        'person_met',
        'person_met_position',
        'person_number',
        'person_adress',
        'follow_up_type',
        'followup_note',
        'done_by_user',
    ];

     protected $casts = [
        'followup_date' => 'datetime',
        'follow_up_type' => ProspectionFollowupType::class,
        'person_met_position' => PositionStatus::class,
    ];

    public function prospection() : BelongsTo
    {
        return $this->belongsTo(Prospection::class, 'prospection_id', 'id_prospection');
    }
    
    public function employee() : BelongsTo
    {
        return $this->belongsTo(Employee::class, 'followup_by_employee', 'employees_Number');
    }

    protected static function booted(): void
    {
        static::creating(function (ProspectionFollowup $followup) {
            $followup->done_by_user = auth()->user()->customer_number ?? 'WebUser';
        });
    }

}