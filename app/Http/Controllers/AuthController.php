<?php

namespace App\Http\Controllers;

use App\Auth\Services\LoginService;
use App\Auth\Services\RegisterService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        return app(RegisterService::class)
            ->setPayload($request)
            ->exec();
    }

    public function login(Request $request)
    {
        return app(LoginService::class)
            ->setPayload($request)
            ->exec();
    }
}
