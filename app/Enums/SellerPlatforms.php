<?php

declare(strict_types=1);

namespace App\Enums;

enum SellerPlatforms: string
{
    case OZON = 'ozon';
    case WILDBERRIES = 'wildberries';

    public function getPlatform(): string
    {
        return $this->value;
    }

    public function readable(): string
    {
        return match ($this) {
            self::OZON => 'Ozon',
            self::WILDBERRIES => 'Wildberries',
        };
    }

    public function forSelect(): array
    {
        return [
            self::OZON->value => 'Ozon',
            self::WILDBERRIES->value => 'Wildberries',
        ];
    }
}
