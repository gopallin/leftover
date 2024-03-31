<?php

namespace App\Auth\Services;

use App\Common\Services\Service;
use App\Repositories\RequestLogRepository;

class CreateRequestLogService extends Service
{
    private $payload;
    private $requestLogRepository;

    public function __construct(
        RequestLogRepository $requestLogRepository
    ) {
        $this->requestLogRepository = $requestLogRepository;
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }

    private function validateRule()
    {
        $this->validate(
            $this->payload,
            [
                'source_ip' => 'required|string',
                'http_method' => 'required|string',
                'domain' => 'required|string',
                'path' => 'required|string',
                'payload' => 'string|nullable'
            ]
        );
    }

    public function exec()
    {
        $this->validateRule();

        return $this->requestLogRepository->create($this->payload);
    }
}
