# PHP wrapper for LogHub.cloud

LogHub is a cloud logging platform to aggregate your logs from all your projects.

```php
require __DIR__ . '/vendor/autoload.php';

use LogHub\LogHub;

LogHub::init('API_KEY');

$logId = LogHub::log(
    'group-1',
    'my actual log content, string, array, class instance or json'
);
```

## Installing

The recommended way to install LogHub PHP is through
[Composer](https://getcomposer.org/).

```bash
composer require oneawebmarketing/loghub-php
```

## Security

If you discover a security vulnerability within this package, please send an email to security@loghub.cloud. All security vulnerabilities will be promptly addressed. Please do not disclose security-related issues publicly until a fix has been announced.

## License

This repository is made available under the MIT License (MIT). Please see [License File](LICENSE) for more information.
