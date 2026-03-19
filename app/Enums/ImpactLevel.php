<?php

namespace App\Enums;

enum ImpactLevel: int
{
    case low = 1;
    case medium = 2;
    case high = 3;

    public function trans(): string
    {
        return match ($this) {
            self::low => app()->getLocale() === 'fr' ? "Faible" : "Low",
            self::medium => app()->getLocale() === 'fr' ? "Moyen" : "Medium",
            self::high => app()->getLocale() === 'fr' ? "Élevé" : "High",
        };
    }
}
