<?php

namespace App\Enums;

Trait EnumTrait {
    public static function toArray() { 
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [
                ($case?->value ?? $case->name) => $case->name,
                //$case->name => ($case?->value ?? $case->name),
            ])->all();
    }

    public function getLabel(): ?string
    {   
        return $this->name;
    }
}