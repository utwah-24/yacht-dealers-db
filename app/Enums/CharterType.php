<?php

namespace App\Enums;

enum CharterType: string
{
    case HalfDay = 'half_day';
    case FullDay = 'full_day';
    case LiveOnboard = 'live_onboard';

    public function label(): string
    {
        return match ($this) {
            self::HalfDay => 'Half day',
            self::FullDay => 'Full day',
            self::LiveOnboard => 'Live Onboard',
        };
    }
}
