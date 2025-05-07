# VaseLekarny Conversion Package

This package provides conversion functionality for VaseLekarny.

## Installation

You can install the package via composer:

```bash
composer require codefit/vl-conversion
```

## Usage

```php
use VaseLekarny\Conversion;

$conversion = new Conversion();
$conversion->setApiKey('your-api-key');

try {
    $result = $conversion->sendConversion([
        // Your conversion data here
    ]);

    // Process the result
} catch (\VaseLekarny\Exceptions\ApiException $e) {
    // Handle API errors
    $errorResponse = $e->getResponse();
}
```

## Conversion Data Structure

When sending a conversion, you need to provide the following data structure:

```php
$conversionData = [
    'order_id' => '12345',           // Required: Unique identifier of the order
    'customer' => [                   // Required: Customer information
        'email' => 'test@example.com', // Required: Customer's email address
        'name' => 'John Doe'          // Required: Customer's full name
    ],
    'products' => [                   // Required: Array of ordered products
        [
            'id' => 'PROD001',        // Required: Product identifier
            'name' => 'Test Product',  // Required: Product name
            'price' => 199.99,        // Required: Product price (including VAT)
            'quantity' => 1           // Required: Quantity of the product
        ]
    ],
    'total_value' => 199.99          // Required: Total order value (including VAT)
];
```

### Required Fields

- `order_id`: Unique identifier of the order (string)
- `customer`: Object containing customer information
  - `email`: Customer's email address (string)
  - `name`: Customer's full name (string)
- `products`: Array of ordered products
  - `id`: Product identifier (string)
  - `name`: Product name (string)
  - `price`: Product price including VAT (float)
  - `quantity`: Quantity of the product (integer)
- `total_value`: Total order value including VAT (float)

## Error Handling

The package throws `VaseLekarny\Exceptions\ApiException` when API requests fail. You can catch these exceptions to handle errors:

```php
try {
    $result = $conversion->sendConversion($data);
} catch (\VaseLekarny\Exceptions\ApiException $e) {
    // Get the error message
    $message = $e->getMessage();

    // Get the full response from the API
    $response = $e->getResponse();
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
