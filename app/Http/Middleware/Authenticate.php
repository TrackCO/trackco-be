<?php

namespace App\Http\Middleware;

use App\Support\Traits\ResponseTrait;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    use ResponseTrait;
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return $this->respondWithCustomData('Unauthorized. Kindly login.', \Illuminate\Http\Response::HTTP_FORBIDDEN);
        }
    }
}
