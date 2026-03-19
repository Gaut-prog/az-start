<?php

namespace App\Enums;

enum ProspectionStatus
{
    case not_prospected;
    case prospected;
    case offer_sent;
    case customer_acquired;

    public function trans() : string
    {
        $version = app()->getLocale();
        
        return match($this){
            self::not_prospected => $version == 'fr' ? "Non prospecté" : "Not prospected",
            self::prospected => $version == 'fr' ? "Prospecté" : "Prospected",
            self::offer_sent => $version == 'fr' ? "Offre envoyée" : "Offer sent",
            self::customer_acquired => $version == 'fr' ? "Client acquis" : "Customer acquired",
        };
    }
}