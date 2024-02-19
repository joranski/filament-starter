<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel
{
    use EnumTrait;

    case Delivered = 'Delivered';
    case Canceled = 'Canceled';
    case ReturnedToVA = 'Returned to VA';
    case VetRefused = 'Vet Refused';
    case Scheduling = 'Scheduling';
    case RepairCompleted = 'Repair Completed';
    case EvaluationForRepair = 'Evaluation For Repair';
    case PendingPartOrder = 'Pending Part Order';
    case PendingWheelchair = 'PendingWheelchair';

    public static function default() { return self::Scheduling->value; }
}