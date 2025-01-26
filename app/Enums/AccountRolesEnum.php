<?php

namespace App\Enums;

enum AccountRolesEnum: int
{
    case BUSINESS_OWNER = 1;
    case EMPLOYEE = 2;
    case INDIVIDUAL = 3;
    case ADMINISTRATOR = 4;

    public static function getDescription(int $value): string{
        return [
            1 => 'Business Owner',
            2 => 'Employee',
            3 => 'Individual',
            4 => 'Administrator'
        ][$value];
    }
}
