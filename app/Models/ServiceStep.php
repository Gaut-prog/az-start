<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServiceStep extends Model
{
    protected $primaryKey = 'step_id';
    protected $table = 'workspace_services_steps';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'default_step_position',
        'step_title',
        'step_description',
        'step_doc',
        'step_doc_name',
        'default_employee_responsable_of',
        'default_partner_responsable_of',
        'default_provider_responsable_of',
        'maximum_day_of_completion',
    ];
    
    protected $hidden = [
        'step_doc'
    ];

    public function getDocAttribute() : string
    {
        return $this->step_doc ? 'data:octet/stream;base64,'.base64_encode($this->step_doc) : '';
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'workspace_services_steps_position', 'service_step_id', 'service_id', 'step_id', 'Items_Numbers')
                    ->withPivot(['position_nb']);
    }
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'default_employee_responsable_of', 'employees_Number');
    }
    
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'default_partner_responsable_of', 'Providers_Numbers');
    }
    
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'default_provider_responsable_of', 'Providers_Numbers');
    }
}