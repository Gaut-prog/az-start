<?php

namespace App\Enums;

enum ImmobilizationUsage
{
    case free;
    case assigned;
    case maintenance;
    case sold;

    public function trans() : string
    {
        $version = app()->getLocale();
        
        return match($this){
            self::free => $version == 'fr' ? "Non utilisé" : "Not used",
            self::assigned => $version == 'fr' ? "En cours d'utilisation" : "In use",
            self::maintenance => $version == 'fr' ? "En maintenance" : "In maintenance",
            self::sold => $version == 'fr' ? "Vendu" : "Sold",
        };
    }
}