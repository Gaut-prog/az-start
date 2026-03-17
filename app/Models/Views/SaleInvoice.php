<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class SaleInvoice extends Model
{
    protected $table = 'sales_invoice';
    protected $connection = 'mysql2';

    protected $casts = [
        'Dates' => 'date' 
    ];

    public function getSaleTypeAttribute()
    {
        $sales_type = '';
        if(app()->getLocale() == 'fr'){
            if($this->subscription_frequency){
                if($this->subscription_frequency == 'week')
                    $sales_type = '/semaine';
                elseif($this->subscription_frequency == 'month')
                    $sales_type = '/mois';
                elseif($this->subscription_frequency == 'year')
                    $sales_type = '/an';
                elseif($this->subscription_frequency == 'minutely')
                    $sales_type = '/minute';
                elseif($this->subscription_frequency == 'hourly')
                    $sales_type = '/heure';
                else
                    $sales_type = '/jour';
            }else{
                if($this->sales_type == 2)
                    $sales_type = '/mois';
                elseif($this->sales_type == 3)
                    $sales_type = '/an';
            }
        }else{
            if($this->subscription_frequency){
                if($this->subscription_frequency == 'week')
                    $sales_type = '/week';
                elseif($this->subscription_frequency == 'month')
                    $sales_type = '/month';
                elseif($this->subscription_frequency == 'year')
                    $sales_type = '/year';
                elseif($this->subscription_frequency == 'minutely')
                    $sales_type = '/minute';
                elseif($this->subscription_frequency == 'hourly')
                    $sales_type = '/hour';
                else
                    $sales_type = '/day';
            }else{
                if($this->sales_type == 2)
                    $sales_type = '/month';
                elseif($this->sales_type == 3)
                    $sales_type = '/year';
            }
        }
        return $sales_type;
    }

    public function getItemNameAttribute()
    {
        return app()->getLocale() == 'fr' ? $this->{'Items Fr_Name'} : $this->{'Items Names'};
    }
    
    public function getTCategoryNameAttribute()
    {
        return app()->getLocale() == 'fr' ? $this->category_fr_name : $this->category_name;
    }
    public function getTabelLAttribute()
    {
        return app()->getLocale() == 'fr' ? $this->fr_label : $this->label;
    }
}