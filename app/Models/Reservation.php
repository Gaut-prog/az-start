<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\QueryBuilders\ReservationQueryBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
     protected $primaryKey = 'reservation_id';
    protected $table = 'reservation';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'customers',
        'sales_invoice',
        'items',
        'group_reservation',
        'employee',
        'starting_date',
        'created_date',
        'ending_date',
        'status',
        'notes',
        'quantities',
        'quantity_2',
        'type',
        'seat_id',
        'User',
    ];
     protected $casts = [
        'starting_date' => 'datetime',
        'created_date' => 'datetime',
        'ending_date' => 'datetime',
    ];

    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class, 'items', 'Items_Numbers');
    }
    
    public function sale() : BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sales_invoice', 'Sales_Numbers');
    }
    
    public function customer() : BelongsTo
    {
        return $this->belongsTo(CompanyCustomer::class, 'customers', 'Customers_Numbers');
    }
    
    public function employ() : BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee', 'employees_Number');
    }
    public function presences() : HasMany
    {
        return $this->hasMany(SubscriptionPresence::class, 'reservation_id', 'reservation_id');
    }

    public function getRemainingMinuteAttribute() : int
    {
        $now = now();
        if ($this->starting_date > $now) {
            return $now->diffInMinutes($this->starting_date);
        }
        return 0;
    }
    
    public function getRemainingDaysAttribute() : int
    {
        if ($this->ending_date > $this->starting_date) {
            return $this->starting_date->diffInDays($this->ending_date);
        }
        return 0;
    }
    
    public function getRemainingHoursAttribute() : int
    {
        if ($this->ending_date > $this->starting_date) {
            return $this->starting_date->diffInHours($this->ending_date);
        }
        return 0;
    }
    public function getRemainingMinutesAttribute() : int
    {
        if ($this->ending_date > $this->starting_date) {
            return $this->starting_date->diffInMinutes($this->ending_date);
        }
        return 0;
    }

    public function newEloquentBuilder($query)
    {
        return new ReservationQueryBuilder($query);
    }
}