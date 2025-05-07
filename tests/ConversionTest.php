<?php

namespace VaseLekarny\Tests;

use PHPUnit\Framework\TestCase;
use VaseLekarny\Conversion;
use VaseLekarny\Exceptions\ApiException;
use VaseLekarny\Requester;

class ConversionTest extends TestCase
{
    private Conversion $conversion;
    private string $testApiKey = 'test-api-key';

    protected function setUp(): void
    {
        $this->conversion = new Conversion();
        $this->conversion->setApiKey($this->testApiKey);
    }

    /**
     * @test
     */
    public function testSuccessfulConversion(): void
    {
        // Mock successful response
        $mockResponse = [
            'status' => 'success',
            'conversion_id' => '123456',
            'message' => 'Conversion processed successfully'
        ];

        // Create a mock for the Requester class
        $requesterMock = $this->createMock(Requester::class);
        $requesterMock->method('sendConversion')
            ->willReturn($mockResponse);

        // Replace the real Requester with our mock
        $reflection = new \ReflectionClass($this->conversion);
        $property = $reflection->getProperty('requester');
        $property->setAccessible(true);
        $property->setValue($this->conversion, $requesterMock);

        $conversionData = [
            'order_id' => '12345',
            'customer' => [
                'email' => 'test@example.com',
                'name' => 'John Doe'
            ],
            'products' => [
                [
                    'id' => 'PROD001',
                    'name' => 'Test Product',
                    'price' => 199.99,
                    'quantity' => 1
                ]
            ],
            'total_value' => 199.99
        ];

        $result = $this->conversion->sendConversion($conversionData);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('conversion_id', $result);
        $this->assertEquals('success', $result['status']);
    }

    /**
     * @test
     */
    public function testFailedConversion(): void
    {
        // Create a mock for the Requester class
        $requesterMock = $this->createMock(Requester::class);
        $requesterMock->method('sendConversion')
            ->willThrowException(new ApiException('Invalid data provided', ['error' => 'Missing required fields']));

        // Replace the real Requester with our mock
        $reflection = new \ReflectionClass($this->conversion);
        $property = $reflection->getProperty('requester');
        $property->setAccessible(true);
        $property->setValue($this->conversion, $requesterMock);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Invalid data provided');

        $invalidData = [
            'order_id' => '12345'
            // Missing required fields
        ];

        $this->conversion->sendConversion($invalidData);
    }

    /**
     * @test
     */
    public function testConversionWithoutApiKey(): void
    {
        $conversion = new Conversion();
        // Not setting API key

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('API key not set');
        
        $conversion->sendConversion(['order_id' => '12345']);
    }

    /**
     * @test
     */
    public function testInvalidApiKey(): void
    {
        $this->conversion->setApiKey('invalid-key');

        // Create a mock for the Requester class
        $requesterMock = $this->createMock(Requester::class);
        $requesterMock->method('sendConversion')
            ->willThrowException(new ApiException('Invalid API key', [], 401));

        // Replace the real Requester with our mock
        $reflection = new \ReflectionClass($this->conversion);
        $property = $reflection->getProperty('requester');
        $property->setAccessible(true);
        $property->setValue($this->conversion, $requesterMock);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Invalid API key');

        $this->conversion->sendConversion([
            'order_id' => '12345',
            'customer' => ['email' => 'test@example.com', 'name' => 'Test User'],
            'products' => [['id' => 'PROD001', 'name' => 'Test', 'price' => 100, 'quantity' => 1]],
            'total_value' => 100
        ]);
    }

    /**
     * @test
     */
    public function testEmptyProductsList(): void
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Products list cannot be empty');

        $this->conversion->sendConversion([
            'order_id' => '12345',
            'customer' => ['email' => 'test@example.com', 'name' => 'Test User'],
            'products' => [],
            'total_value' => 0
        ]);
    }
} 