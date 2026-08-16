# Inlomax PHP SDK

The official PHP SDK for the [Inlomax API](https://inlomax.com/docs). This SDK makes it easy for developers to integrate Inlomax services like Airtime, Data, Electricity, and KYC verifications into their PHP applications.

## Requirements

- PHP >= 7.4
- Composer

## Installation

You can install the package via composer:

```bash
composer require inlomax/inlomax-php
```

*(Note: Until published on Packagist, you can install it locally by configuring a local composer repository).*

## Usage

### Initialization

Initialize the client with your API key. You can find your API key in your Inlomax developer dashboard.

```php
require_once 'vendor/autoload.php';

use Inlomax\Inlomax\Client;

// Provide your API Key. Set the second parameter to true if you are using the sandbox environment.
$apiKey = 'YOUR_API_KEY';
$isSandbox = false;

$inlomax = new Client($apiKey, $isSandbox);
```

### Get Wallet Balance

```php
try {
    $response = $inlomax->getBalance();
    print_r($response);
} catch (\Inlomax\Inlomax\Exceptions\InlomaxException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Buy Airtime

```php
try {
    $payload = [
        "serviceID" => "100", // Check services endpoint for IDs
        "amount" => 200,
        "mobileNumber" => "0903837261",
        "request-id" => uniqid() // Ensure this is unique per transaction
    ];
    $response = $inlomax->buyAirtime($payload);
    print_r($response);
} catch (\Inlomax\Inlomax\Exceptions\InlomaxException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Buy Data

```php
try {
    $payload = [
        "serviceID" => "100",
        "mobileNumber" => "0903837261",
        "request-id" => uniqid()
    ];
    $response = $inlomax->buyData($payload);
    print_r($response);
} catch (\Inlomax\Inlomax\Exceptions\InlomaxException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Pay for Electricity

```php
try {
    $payload = [
        "serviceID" => "1",
        "amount" => 1000,
        "meterNumber" => "11111111111", // Example field, check API docs for exact fields required
        "request-id" => uniqid()
    ];
    $response = $inlomax->buyElectricity($payload);
    print_r($response);
} catch (\Inlomax\Inlomax\Exceptions\InlomaxException $e) {
    echo "Error: " . $e->getMessage();
}
```

## Available Methods

- `getBalance()`
- `getServices()`
- `buyAirtime(array $payload)`
- `buyData(array $payload)`
- `buyCable(array $payload)`
- `buyElectricity(array $payload)`
- `buyEducationPins(array $payload)`
- `verifyBankAccount(array $payload)`
- `verifyBvn(array $payload)`
- `verifyNin(array $payload)`
- `verifyIuc(array $payload)`
- `verifyMeter(array $payload)`
- `getTransaction(string $reference)`

## Support

For any difficulty integrating the API, please refer to the [official documentation](https://inlomax.com/docs) or contact support.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
