[![PHPUnit](https://github.com/oyale/PwPush-PHP/actions/workflows/php.yml/badge.svg)](https://github.com/oyale/PwPush-PHP/actions/workflows/php.yml) [![Test Coverage](https://raw.githubusercontent.com/oyale/PwPush-PHP/main/badge-coverage.svg)](https://packagist.org/packages/oyale/pwpush-php)
[![Semantic Versions](https://img.shields.io/badge/%20%20%F0%9F%93%A6%F0%9F%9A%80-semantic--versions-e10079.svg)](https://github.com/oyale/PwPush-PHP/releases) 
# PwPush-PHP

> A PHP library wrapper to easily push passwords to any [PasswordPusher](https://github.com/pglombardo/PasswordPusher) instance

![image](https://user-images.githubusercontent.com/2450417/37249539-122d2056-24c8-11e8-860c-ca4609ef4073.png)

## Install
```bash
composer require oyale/pwpush-php
```

## Usage
```php

// Push a secret (returns the URL)
PwPush::push(string $secret, ?array $options[] = null, ?string $urlBase = 'https://pwpush.com', ?bool $validate = false);

// Retrieve a secret
PwOps::get(string $token, ?string $urlBase="https://pwpush.com");

# Delete a secret
PwOps::delete(string $token, ?string $urlBase="https://pwpush.com");
```

## `PwPush::push` Parameters
### `$secret`
**Required**. \
The secret to be pushed\
Type: `string`

### `$options`
Type: `array` \
Default value: `server configuration`

### `$urlBase`
URL of PwPush instance \
Type: `string` \
Default value: `https://pwpush.com`

### `$validate`
Validate JSON against schema prior to push secret\
Type: `bool` \
Default value: `false`

### Options
`$options` is a key-value array. Valid options are:

####  expire_after_days
Number of days until the password is deleted.\
Type: `integer` \
Default value: `server configuration`

####  expire_after_views
Number of visualizations until the password is deleted.\
Type: `integer` \
Default value: `server configuration`

#### retrieval_step
Helps to avoid chat systems and URL scanners from eating up views.\
Type: `bool` \
Default value: `server configuration`

#### deletable_by_viewer
Allow users to delete passwords once retrieved.\
Type: `bool` \
Default value: `server configuration`

## `PwOps` Parameters
### `token`
**Required**. \
Token for the secret\
Type: `string`

### `$urlBase`
URL of PwPush instance \
Type: `string` \
Default value: `https://pwpush.com`

## Examples
You can see a complete workflow example in [examples/workflow.php](examples/workflow.php)

## License
[LGPL-3.0](LICENSE)
