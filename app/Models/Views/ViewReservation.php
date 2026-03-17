<?php

namespace App\Models\Views;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ViewReservation extends Model
{
    protected $table = 'viewresertation';
    protected $connection = 'mysql2';

    protected $casts = [
        'starting_date' => 'datetime',
        'ending_date' => 'datetime',
        'reserved_date' => 'datetime',
    ];

    public function reservation() : HasOne
    {
        return $this->hasOne(Reservation::class, 'reservation_id', 'reservation_id');
    }

    public function getRemainingDaysAttribute() : int
    {
        $starting_date = now();
        if ($this->starting_date > $starting_date) { //si la date de début est encore devant 
            $starting_date = $this->starting_date;
        }
        if ($this->ending_date > $starting_date) {
            return $starting_date->diffInDays($this->ending_date);
        }
        return 0;
    }
    
    public function getRemainingHoursAttribute() : int
    {
        $starting_date = now();
        if ($this->starting_date > $starting_date) { //si la date de début est encore devant 
            $starting_date = $this->starting_date;
        }
        if ($this->ending_date > $starting_date) {
            return $starting_date->diffInHours($this->ending_date);
        }
        
        return 0;
    }
    public function getRemainingMinutesAttribute() : int
    {
        $starting_date = now();
        if ($this->starting_date > $starting_date) { //si la date de début est encore devant 
            $starting_date = $this->starting_date;
        }
        if ($this->ending_date > $starting_date) {
            return $starting_date->diffInMinutes($this->ending_date);
        }
        
        return 0;
    }

    public function getItemNameAttribute(): ?string
    {
        if($this->{'Items Fr_Name'} || $this->{'Items Names'})
            return app()->getLocale() == 'fr' ? $this->{'Items Fr_Name'} : $this->{'Items Names'};
        else
            return $this->items;
    }
    
    public function getCustomerNameAttribute(): ?string
    {
        return $this->customers_lastname . ' '. $this->customers;
    }
}