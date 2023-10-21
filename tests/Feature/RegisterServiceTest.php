<?php

namespace Tests\Feature;

use App\Auth\Services\RegisterService;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RegisterServiceTest extends TestCase
{
    public function test_it_can_throw_validation_exception(): void
    {
        $this->withoutExceptionHandling();

        $this->expectException(ValidationException::class);

        app(RegisterService::class)
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
