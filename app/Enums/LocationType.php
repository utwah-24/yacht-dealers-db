<?php

namespace App\Enums;

enum LocationType: string
{
    case DarEsSalaam = 'dar_es_salaam';
    case Zanzibar = 'zanzibar';

    public function label(): string
    {
        return match ($this) {
            self::DarEsSalaam => 'Dar es Salaam',
            self::Zanzibar => 'Zanzibar',
        };
    }
}
