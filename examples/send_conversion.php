<?php

require_once __DIR__ . '/../vendor/autoload.php';

use VaseLekarny\Conversion;
use VaseLekarny\Exceptions\ApiException;

// Initialize the conversion client
$conversion = new Conversion();

// Set your API key
$conversion->setApiKey('your-api-key-here');

try {
    // Example conversion data
    $data = [
        'order_id' => '12345',
        'customer' => [
            'email' => 'customer@example.com',
            'name' => 'John Doe'
        ],
        'products' => [
            [
                'id' => 'PROD001',
                'name' => 'Product 1',
                'price' => 750.00,
                'quantity' => 1
            ],
            [
                'id' => 'PROD002',
                'name' => 'Product 2',
                'price' => 750.00,
                'quantity' => 1
            ]
        ],
        'total_value' => 1500.00
    ];

    // Send the conversion
    $result = $conversion->sendConversion($data);
    
    // Handle the response
    echo "Conversion sent successfully!\n";
    print_r($result);
    
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 