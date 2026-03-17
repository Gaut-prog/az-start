<?php

namespace App\Models\Views;

use App\Action\Helpers;
use Illuminate\Database\Eloquent\Model;

class AZCompanyInfo extends Model
{
    protected $primaryKey = 'Numbers';
    protected $table = 'az_customers_info';

    public function getCodeIdAttribute() : string
    {
        return Helpers::codeId($this->Numbers);
    }
    
    public function getLogoAttribute() : string
    {
        return $this->Picture ? 'data:image/jpeg;base64,'.base64_encode($this->Picture) : '';
    }
    
    public function getCityAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->City_FR_Name : $this->City_Name;
    }

    public function getCountryAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->Country_FR_Name : $this->Country_Name;
    }

    public function getProvinceAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->Province_FR_Name : $this->Province_Name;
    }

    public function getBranchAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->{"S.Branch FR_Name"} : $this->{"S.Branch Name"};
    }
    
    public function getAddressAttribute() : ?string
    {
        $address='';
        if($this->Appt){
            $address .= $this->Appt;
        }
        if($this->Adresses){
            if(strlen($address)){
                $address .=' - ';
            }
            $address .= $this->Adresses;
        }
        if($this->city){
            if(strlen($address)){
                $address .=', ';
            }
            $address .= $this->city;
        }
        if($this->country){
            if(strlen($address)){
                $address .=' - ';
            }
            $address .= $this->country;
        }
       
        if($this->Postal_Code){
            if(strlen($address)){
                $address .=', ';
            }
            $address .= $this->Postal_Code;
        }
        return $address;
    }
}