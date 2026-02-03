# Ecomail GoSms

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.4-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%20max-brightgreen.svg)](https://phpstan.org/)
[![Pest](https://img.shields.io/badge/Pest-v4-f472b6.svg)](https://pestphp.com/)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](https://github.com/ecomailcz/ecomail-gosms)

Laravel package providing a client for the [GoSms.cz](https://gosms.cz) API.

It supports sending single SMS, bulk sending, connection verification, and message status queries via a Laravel application using the facade and service provider.

---

## Features

| Component | Purpose |
|-----------|---------|
| **GoSms API v2** | Single SMS, bulk send, message status |
| **Laravel integration** | Service provider, `GoSms` facade, config via `config/gosms.php` |
| **[Pest](https://pestphp.com/)** | Testing with 100% coverage requirement |
| **[PHPStan](https://phpstan.org/)** | Static analysis at maximum level |
| **[Laravel Pint](https://laravel.com/docs/pint)** | Consistent code style |
| **[Rector](https://getrector.org/)** | Automated refactoring |
| **[PHP CodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer)** | Coding standard enforcement |
| **[Security Advisories](https://github.com/Roave/SecurityAdvisories)** | Dependency vulnerability checking |

---

## Installation

```bash
composer require ecomailcz/ecomail-gosms
```

After installation, run the install command to copy the config to `config/gosms.php` and display `.env` instructions:

```bash
php artisan gosms:install
```

Alternatively, you can publish only the config manually:

```bash
php artisan vendor:publish --tag=gosms-config
```

Add to your `.env`:

```
GOSMS_CLIENT_ID=your_client_id
GOSMS_CLIENT_SECRET=your_client_secret
GOSMS_DEFAULT_CHANNEL=1
```

---

## Configuration

The package registers config from `config/gosms.php` (values from `.env`). The service provider and facade are loaded automatically (Laravel package discovery).

Usage via facade:

```php
use EcomailGoSms\Laravel\GoSmsFacade as GoSms;

// Verify connection
GoSms::authenticate();

// Send a single SMS
GoSms::sendMessageAsync('+420123456789', 'Message text');

// Bulk send
GoSms::sendMessagesAsync(['+420123456789', '+420987654321'], 'Shared text');
```

More examples and how to run the example scripts are in [examples/README.md](examples/README.md).

---

## Available Commands

### Run All Quality Checks

```bash
composer check
```

Runs the full quality pipeline:
- Composer normalize
- PHP CodeSniffer
- Laravel Pint
- Rector
- PHPStan
- Security audit
- Tests with 100% coverage

### Apply All Fixes

```bash
composer fix
```

Automatically fixes code style and applies refactoring:
- Composer normalize
- Rector refactoring
- Laravel Pint formatting
- PHP CodeSniffer fixes

### Individual Commands

| Command | Description |
|---------|-------------|
| `composer test` | Run tests |
| `composer test:coverage` | Run tests with coverage (min 100%) |
| `composer analyse` | Run PHPStan |
| `composer pint-check` | Check code style |
| `composer pint-fix` | Fix code style |
| `composer rector-check` | Check for refactoring opportunities (dry-run) |
| `composer rector-fix` | Apply refactoring |
| `composer phpcs-check` | Check coding standards |
| `composer phpcs-fix` | Fix coding standards |
| `composer normalize-check` | Check composer.json normalization |
| `composer normalize-fix` | Normalize composer.json |
| `composer security-audit` | Check for vulnerable dependencies |

---

## Project Structure

```
ecomail-gosms/
├── .github/
│   └── workflows/
│       ├── pr.yml                 # Checks on PR and push
│       ├── composer-update.yml    # Automated dependency updates
│       └── update-changelog.yml  # Changelog updates
├── examples/
│   ├── README.md                 # Examples docs and how to run
│   ├── .env.example
│   ├── authenticate.php
│   ├── send-single-message.php
│   ├── send-bulk-messages.php
│   └── bulk-send-and-wait-for-sent.php
├── src/
│   ├── GoSmsClient.php
│   ├── Client.php
│   ├── Message.php
│   ├── SentMessage.php
│   ├── Contracts/
│   ├── Data/
│   ├── Exceptions/
│   ├── Http/
│   ├── Laravel/
│   ├── Requests/
│   └── Responses/
├── tests/
│   ├── Unit/
│   └── Fixtures/
├── composer.json
├── phpstan.neon
├── pint.json
├── rector.php
├── ruleset.xml
└── LICENSE
```

---

## Configuration

### PHPStan

Static analysis runs at **max level** with additional rules (deprecation, Mockery).

### Testing

Tests use **Pest v4** with a 100% coverage requirement:

```bash
composer test:coverage
```

### Code Style

Laravel Pint enforces PSR-12 and project rules.

### Rector

Refactoring uses rules from the `pekral/rector-rules` package.

---

## GitHub Actions

The repository uses these workflows:

| Workflow | Purpose |
|----------|---------|
| `pr.yml` | Run all checks on pull requests and push |
| `composer-update.yml` | Automated dependency updates |
| `update-changelog.yml` | Automated changelog updates |

---

## Requirements

- PHP 8.4 or higher
- Laravel 12.x
- Composer 2.x

---

## Contributing

Contributions are welcome. Open an issue or submit a Pull Request.

---

## License

This package is open-source software licensed under the [MIT License](LICENSE).

---

## Author

**Ecomail.cz**

- Email: info@ecomail.cz
