# ecomail-gosms

gosms.cz API client

## About

GoSMS.cz API client for PHP enables simple integration with GoSMS API for sending SMS messages. The client supports authentication, asynchronous message sending, and delivery status tracking.

## Installation

```bash
composer require ecomailcz/gosms-client
```

## Usage

### Basic Usage

```php
use EcomailGoSms\GoSmsClient;
use EcomailGoSms\Message;

// Initialize client with API credentials
$client = new GoSmsClient(
    new \GuzzleHttp\Client(),
    'your_client_id',
    'your_client_secret'
);

// Authentication
$authClient = $client->authenticate();
$accessToken = $authClient->getAccessToken();

// Send SMS
$message = new Message(
    'Hello World!',
    123456, // channel ID
    '+420123456789',
    'custom-id-123'
);

$response = $client->sendMessageAsync($message);
```

### Laravel Integration

The client has built-in support for Laravel framework. It automatically registers during installation.

```php
// Usage in Laravel
$smsClient = app(GoSmsClient::class);

// Or using facade
use EcomailGoSms\Laravel\GoSmsFacade as GoSms;

GoSms::sendMessageAsync($message);
```

### Batch Sending

```php
$messages = [
    new Message('Message 1', $channelId, '+420111111111', 'id1'),
    new Message('Message 2', $channelId, '+420222222222', 'id2'),
];

$response = $client->sendMessagesAsync($messages);
```

### Message Status Tracking

```php
// Get message status
$status = $client->getMessageStatistics('custom-id-123');
$messages = $status->getMessages();
```

## Configuration

### Environment Variables

For Laravel applications, add to your `.env` file:

```env
GOSMS_CLIENT_ID=your_client_id
GOSMS_CLIENT_SECRET=your_client_secret
GOSMS_CHANNEL_ID=your_default_channel_id
```

### Laravel Configuration

The client automatically configures through the service provider. For custom configuration, create `config/gosms.php`:

```php
return [
    'client_id' => env('GOSMS_CLIENT_ID'),
    'client_secret' => env('GOSMS_CLIENT_SECRET'),
    'channel_id' => env('GOSMS_CHANNEL_ID'),
];
```

## API Reference

### Authentication

The client supports OAuth2 authentication with automatic refresh token management.

### Exceptions

The client may throw the following exceptions:
- `BadRequest` - invalid request
- `UnauthorizedRequest` - unauthorized access
- `InvalidRequest` - invalid parameters
- `InvalidResponseData` - invalid API response
- `GeneralException` - general error

## Testing

The project includes comprehensive tests with 100% code coverage:

```bash
composer test
composer coverage
```

## Quality Assurance

The project uses the following tools to ensure code quality:

### Code Analysis
```bash
composer analyse  # PHPStan static analysis
```

### Code Formatting
```bash
composer pint-check  # Check formatting
composer pint-fix    # Fix formatting
```

### Code Quality
```bash
composer phpcs-check  # Check coding standards
composer phpcs-fix    # Fix coding standards
```

### Refactoring
```bash
composer rector-check  # Check for improvements
composer rector-fix    # Apply improvements
```

### Full Quality Check
```bash
composer check  # Run all quality checks
composer fix    # Apply all fixes
```

## Requirements

- PHP 8.4+
- Composer
- Guzzle HTTP client

## Contributing

Contributions are welcome! Before submitting a pull request, please run:

```bash
composer check
composer test
```

## Security Vulnerabilities

If you discover a security vulnerability, please contact us at security@ecomail.cz.

## Credits

- Petr Král
- Ecomail.cz team

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
