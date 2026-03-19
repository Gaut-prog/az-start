<?php

namespace App\Enums;

enum PositionStatus
{
    case owner;
    case manager;
    case assistant;
    case other;

    public function trans() : string
    {
        $version = app()->getLocale();
        
        return match($this){
            self::owner => $version == 'fr' ? "Propriétaire" : "Owner",
            self::manager => $version == 'fr' ? "Gérant" : "Manager",
            self::assistant => $version == 'fr' ? "Assistant" : "Assistant",
            self::other => $version == 'fr' ? "Autre" : "Other",
        };
    }
}