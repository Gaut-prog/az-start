<?php

namespace App\Enums;

enum ExpenseCategory : int
{
    case unique = 1;
    case monthly = 2;
    case yearly = 3;

    public function trans() : string
    {
        $version = app()->getLocale();
        
        return match($this){
            self::unique => $version == 'fr' ? "Dépense unique" : "One-time expense",
            self::monthly => $version == 'fr' ? "Dépense mensuelle" : "Monthly expense",
            self::yearly => $version == 'fr' ? "Dépense annuelle" : "Yearly expense",
            default => "",
        };
    }
}