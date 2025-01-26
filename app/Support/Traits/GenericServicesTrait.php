<?php

namespace App\Support\Traits;

use Illuminate\Support\Facades\Auth;

trait GenericServicesTrait
{
    private static string $guard = 'api';

    private static function user()
    {
        return Auth::guard(self::$guard)->user();
    }

}
