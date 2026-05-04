# Telr Payment Gateway (Laravel)

PHP package for integrating [Telr](https://www.telr.com/) REST orders with Laravel applications. It creates SDK-backed payment orders and verifies order status using Basic authentication against Telr’s API.

## Requirements

- PHP 8.1 or higher (match your Laravel version’s PHP requirement)
- Laravel 9, 10, 11, or 12 (`illuminate/support` and `illuminate/http`)

## Installation

```bash
composer require ahmedtaha-dev/telr-payment-gateway
```

## Laravel setup

### 1. Register the service provider

**Laravel 11+** — add the provider to `bootstrap/providers.php`:

```php
<?php

return [
    // ...
    AhmedTaha\Telr\TelrServiceProvider::class,
];
```

**Laravel 9–10** — add to the `providers` array in `config/app.php`:

```php
AhmedTaha\Telr\TelrServiceProvider::class,
```

### 2. (Optional) Register the facade alias

In `config/app.php` under `aliases`:

```php
'Telr' => AhmedTaha\Telr\Facades\Telr::class,
```

You can also resolve the service without a facade: `app(AhmedTaha\Telr\TelrService::class)`.

### 3. Publish configuration

```bash
php artisan vendor:publish --tag=telr-config
```

This copies `config/telr.php` into your application so you can adjust defaults.

## Configuration

Set `TELR_MODE` to `test` or `live`. Credentials and base URL are read from the block that matches the active mode.

| Variable | Description |
|----------|-------------|
| `TELR_MODE` | `test` (default) or `live` |
| `TELR_TEST_STORE_ID` | Test store ID |
| `TELR_TEST_AUTH_KEY` | Test authentication key |
| `TELR_TEST_BASE_URL` | Test API base URL (no trailing slash; paths `/orders` and `/orders/{ref}` are appended) |
| `TELR_LIVE_STORE_ID` | Live store ID |
| `TELR_LIVE_AUTH_KEY` | Live authentication key |
| `TELR_LIVE_BASE_URL` | Live API base URL |

Use the base URL from [Telr’s developer documentation](https://docs.telr.com/) for your environment (for example, the host and API prefix you use for `POST …/orders` and `GET …/orders/{ref}`).

## Usage

### Create an SDK payment order

Build a `TelrSdkDTO` with your cart and customer details, then call `createSDKPaymentTransaction`. The `test` flag in the request body is set automatically from `TELR_MODE`.

```php
use AhmedTaha\Telr\DTO\TelrSdkDTO;
use AhmedTaha\Telr\Facades\Telr;

$dto = new TelrSdkDTO([
    'cartid' => 'ORDER-10042',
    'amount' => 99.50,
    'currency' => 'AED',
    'description' => 'Order payment',
    'customer' => [
        'ref' => 'user-42',
        'email' => 'customer@example.com',
    ],
]);

$result = Telr::createSDKPaymentTransaction($dto);
```

Returned array keys:

| Key | Meaning |
|-----|---------|
| `token_url` | URL from `_links.auth` (for Telr SDK / hosted flow) |
| `order_url` | URL from `_links.self` |
| `ref` | Order reference at Telr |
| `response_data` | Full decoded JSON response |

On non-success HTTP responses, a `AhmedTaha\Telr\Exceptions\TelrException` is thrown with the response body in the message.

### Verify an order

After payment, confirm status using the order reference returned when the order was created:

```php
use AhmedTaha\Telr\Facades\Telr;

$order = Telr::verifyTransaction($ref);
```

Returns the decoded JSON from Telr. Failures throw `TelrException`.

### Dependency injection

```php
use AhmedTaha\Telr\TelrService;

public function __construct(protected TelrService $telr) {}

$this->telr->createSDKPaymentTransaction($dto);
$this->telr->verifyTransaction($ref);
```

## Telr documentation

- [Create order](https://docs.telr.com/reference/createorder)
- [Get order details](https://docs.telr.com/reference/getorder)

## License

MIT. See `composer.json` for the declared license.
