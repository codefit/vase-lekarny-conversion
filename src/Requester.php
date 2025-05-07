<?php

namespace VaseLekarny;

use VaseLekarny\Exceptions\ApiException;

class Requester
{
    private string $apiKey;
    private string $baseUrl = 'https://vase-lekarny.cz/api/v1/conversion/';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Send a conversion request to the API
     *
     * @param array $data
     * @return array
     * @throws ApiException
     */
    public function sendConversion(array $data): array
    {
        // Validate required fields
        $this->validateConversionData($data);

        $ch = curl_init($this->baseUrl);
        
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey,
                'Accept: application/json'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            throw new ApiException('API request failed: ' . curl_error($ch));
        }
        
        curl_close($ch);

        if ($response === false) {
            throw new ApiException('Failed to get response from API');
        }

        $responseData = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException('Invalid JSON response from API: ' . json_last_error_msg());
        }

        if ($httpCode >= 400) {
            throw new ApiException(
                'API request failed with status ' . $httpCode,
                $responseData ?? [],
                $httpCode
            );
        }

        return $responseData ?? [];
    }

    /**
     * Validate conversion data
     *
     * @param array $data
     * @throws ApiException
     */
    private function validateConversionData(array $data): void
    {
        if (empty($data['products'])) {
            throw new ApiException('Products list cannot be empty');
        }

        if (empty($data['customer']['email']) || empty($data['customer']['name'])) {
            throw new ApiException('Customer email and name are required');
        }

        if (empty($data['order_id'])) {
            throw new ApiException('Order ID is required');
        }

        if (!isset($data['total_value']) || !is_numeric($data['total_value'])) {
            throw new ApiException('Total value must be a number');
        }
    }
} 