<?php

namespace App\Http\Controllers;

use App\Auth\Services\LoginService;
use App\Auth\Services\RegisterService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $response = app(RegisterService::class)
            ->setPayload($request)
            ->exec();

        return ok(['data' => $response]);
    }

    public function login(Request $request)
    {
        $response = app(LoginService::class)
            ->setPayload($request)
            ->exec();

        return ok(['data' => $response]);
    }
}
