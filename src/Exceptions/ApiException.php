<?php

namespace VaseLekarny\Exceptions;

class ApiException extends \Exception
{
    protected array $response;

    public function __construct(string $message, array $response = [], int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    public function getResponse(): array
    {
        return $this->response;
    }
} 