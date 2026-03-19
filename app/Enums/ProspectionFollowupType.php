<?php

namespace App\Enums;

enum ProspectionFollowupType
{
    case first_contact;
    case follow_up_before_sale;
    case follow_up_after_sale;
    case training;
    case support;

    public function trans() : string
    {
        $version = app()->getLocale();
        
        return match($this){
            self::first_contact => $version == 'fr' ? "Premier contact" : "First contact",
            self::follow_up_before_sale => $version == 'fr' ? "Suivi avant vente" : "Followup before sale",
            self::follow_up_after_sale => $version == 'fr' ? "Suivi après vente" : "Followup after sale",
            self::training => $version == 'fr' ? "Formation" : "Training",
            self::support => $version == 'fr' ? "Support" : "support",
        };
    }
}