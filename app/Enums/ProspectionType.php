<?php

namespace App\Enums;

enum ProspectionType
{
    case partnership;
    case customer;

    public function trans() : string
    {
        $version = app()->getLocale();
        
        return match($this){
            self::partnership => $version == 'fr' ? "Partenariat" : "Partnership",
            self::customer => $version == 'fr' ? "Client" : "Customer",
        };
    }
}