<?php

namespace App\Common\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class Service
{
    public function __construct()
    {
    }

    abstract public function exec();

    public function validate(
        array $inputs,
        array $rules,
        array $messages = []
    ) {
        $validator = Validator::make($inputs, $rules, $messages);

        if ($validator->fails()) {
            $errorMessage = '';

            foreach ($validator->errors()->toArray() as $key => $errors) {
                foreach ($errors as $error) {
                    $errorMessage  .= PHP_EOL . $error;
                }
            }

            throw ValidationException::withMessages(['message' => $errorMessage]);
        }
    }
}
