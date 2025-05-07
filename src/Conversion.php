<?php

namespace VaseLekarny;

use VaseLekarny\Exceptions\ApiException;

class Conversion
{
    private ?Requester $requester = null;

    /**
     * Create a new Conversion instance.
     */
    public function __construct()
    {
        // Initialize the conversion
    }

    /**
     * Set the API key for authentication
     *
     * @param string $apiKey
     * @return self
     */
    public function setApiKey(string $apiKey): self
    {
        $this->requester = new Requester($apiKey);
        return $this;
    }

    /**
     * Send a conversion request
     *
     * @param array $data
     * @return array
     * @throws ApiException
     */
    public function sendConversion(array $data): array
    {
        if (!$this->requester) {
            throw new ApiException('API key not set. Please call setApiKey() first.');
        }

        return $this->requester->sendConversion($data);
    }

    /**
     * Get the package version.
     *
     * @return string
     */
    public function version(): string
    {
        return '1.0.0';
    }
} 