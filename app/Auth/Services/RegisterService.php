<?php

namespace App\Auth\Services;

use App\Common\Services\Service;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterService extends Service
{
    private $payload;
    private $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }

    private function validateRule()
    {
        $this->validate(
            $this->payload->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required',
                'password' => [
                    'required',
                    Password::min(8)->numbers()->uncompromised()
                ]
            ],
            [
                'email.required' => 'We need to know your email address!',
                'name.required' => 'We need to know your name!',
                'name.max' => 'Your name is too long!',
                'password.required' => 'We need to know your password!'
            ]
        );
    }

    public function exec()
    {
        $this->validateRule();

        $user = $this->userRepository->search(['name' => $this->payload->name])->first();

        if ($user) {
            throw new Exception('yoyo');
        }

        $user = $this->userRepository->search(['email' => $this->payload->email])->first();

        if ($user) {
            throw new Exception('hoho');
        }

        $this->payload['password'] = Hash::make($this->payload->password);
        $user = $this->userRepository->create($this->payload->toArray());

        Auth::login($user);
    }
}
