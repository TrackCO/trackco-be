<?php

namespace App\Enums;

enum AccountType: int
{
    case BUSINESS = 1;
    case INDIVIDUAL = 2;
    case ADMIN = 3;

    public static function getDescription(int $value): string{
        return [
            1 => 'Business Account',
            2 => 'Individual Account',
            3 => 'Admin'
        ][$value];
    }
}
