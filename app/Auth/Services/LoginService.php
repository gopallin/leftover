<?php

namespace App\Auth\Services;

use App\Repositories\UserRepository;
use App\Common\Services\Service;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class LoginService extends Service
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
                'password.required' => 'We need to know your password!',
                'required' => 'The :attribute field is required.',
            ]
        );
    }

    public function exec()
    {
        $this->validateRule();

        if (isset($this->payload->name)) {
            $user = $this->userRepository->search(['name' => $this->payload->name])->first();
        } else {
            $user = $this->userRepository->search(['email' => $this->payload->email])->first();
        }

        if (!$user) {
            throw new Exception('yoyo');
        }

        $isPasswordCorrect = Hash::check($this->payload->password, $user->password);

        if (!$isPasswordCorrect) {
            throw new Exception('hoho');
        }

        Auth::login($user);
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        $this->userRepository->update($user->id, ['recently_login' => Carbon::now()]);

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
