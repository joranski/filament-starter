<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ServiceType: string implements HasLabel
{
    use EnumTrait;

    case ServiceParts = 'Service / Parts';
    case SetupATP = 'Setup / ATP';
    case HMEDelivery = 'HME Delivery';

    public static function default() { return self::ServiceParts->value; }
}