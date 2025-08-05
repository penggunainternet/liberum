<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as ContractsLoginResponse;

class LoginResponse implements ContractsLoginResponse
{
    public function toResponse($request)
    {
        // Redirect semua user (termasuk admin) ke halaman utama
        return redirect()->intended(config('fortify.home'));
    }
}
