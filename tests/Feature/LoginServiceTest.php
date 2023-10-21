<?php

namespace Tests\Feature;

use App\Auth\Services\LoginService;
use Exception;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class LoginServiceTest extends TestCase
{
    public function test_it_can_throw_validation_exception(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        app(LoginService::class)
            ->setPayload(
                collect([
                    'name' => '',
                    'email' => '',
                    'password' => ''
                ])
            )
            ->exec();
    }

    public function test_it_can_throw_exception_with_user_not_found()
    {
        $user = $this->createUser();
        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);

        app(LoginService::class)
            ->setPayload(
                collect([
                    'name' => '',
                    'email' => '',
                    'password' => ''
                ])
            )
            ->exec();
    }
}
