<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, WithFaker;

    protected function createUser()
    {
        return User::create(
            [
                'name' => 'gopal.lin',
                'email' => 'gopal.lin@evolutivelabs.com',
                'password' => 'gopal.lin'
            ]
        );
    }
}
