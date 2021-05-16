# Laravel Token Login

Allows to login an user by an unique token.

> Security Concerns: Using tokens for login is a security concern because it’s the equivalent of storing a password in plain text.

## Table of Contents

- [Background](#background)
- [Install](#install)
- [Usage](#usage)
    - [Concerns](#concerns)
    - [Token creation](#token-creation)
    - [Login](#login)
    - [Refresh tokens](#refresh-tokens)
    - [Delete expired tokens](#delete-expired-tokens)
    - [Delete all tokens](#delete-all-tokens)
    - [Exclude users](#exclude-users)
  - [Exclude users](#exclude-users)
- [Contributing](#contributing)
- [License](#license)

## Background

For private and closed applications we created this package to allow users to quickly sign in.
For example: We provide updates to users by mail, in this mail we provide a one click login url (with an unique token for each user).
The user doesn't have to fill in the login credentials and can quickly see it's private data.

## Install

This project uses [Laravel 7+](https://laravel.com) and requires PHP 7.4 as minimum version.

```sh
composer require wefabric/token-login
```

Publish the config and migration:
```sh
php artisan vendor:publish --provider='Wefabric\TokenLogin\Providers\ServiceProvider'
```
Check the configuration (config/token-login.php). By the default the token and expiration will be setup for the default User model.
To use it with a custom model change the configuration accordingly. When you are all setup. Run the migration
```sh
php artisan migrate
```

## Usage


This package adds commands to manage the tokens. 
By default configuration the user table will contain two new fields ('login_token' and 'login_token_expires_at').

### Concerns
Add the following traits to the user model.

```php
use Wefabric\TokenLogin\Concerns\HasTokenLogin;
use Wefabric\TokenLogin\Concerns\HasTraitsWithCasts;

class User extends Authenticatable
{
    use HasTokenLogin, HasTraitsWithCasts;
```

### Token creation
After the traits are added, you need to run the following command to generate the tokens and expiration dates.
```sh
php artisan token-login:create
```
### Login
When the tokens are created, it is possible to login by the token.
The package adds the route /users/token-login by default (to change it, change the 'login_path' variable in the config/token-login.php file). You can do a GET and POST request to login, like the following:
> https://site.test/users/token-login?token={TOKEN}

When the token is correct and not expired. The user will be redirected to the default redirect path (see config/token-login.php).
You can also specify a specific redirect as second parameter.
> https://site.test/users/token-login?token={TOKEN}&redirect=https://site.test/my-redirect

### Refresh tokens
The token expires after a certain period. To refresh the tokens, you can use the following command.
```sh
php artisan token-login:refresh
```
This will refresh all expired tokens.
> Use this command in your scheduler. For example, let it run every five minutes.

### Delete expired tokens
To delete all expired tokens run the following command
```sh
php artisan token-login:delete-expired
```

### Delete all tokens
To delete all tokens run the following command
```sh
php artisan token-login:delete
```

### Exclude users
To exclude users from the token generation, change the following in the config (config/token-login.php)
```php
'not_allowed' => [
    'key' => 'id',
    'items' => [
        1
    ]
]
```

### Helper
There is a helper available for using this package.

To check if the token login is enabled:
```php
echo tokenLogin()->enabled();
```

To retrieve the login url for a specific model:
```php
echo tokenLogin()->loginUrl(User::first(), 'https://site.test/redirect');
```

## Maintainers

[@leoflapper](https://github.com/leoflapper).

## Contributing

Feel free to dive in! [Open an issue](https://github.com/wefabric/token-login/issues/new) or submit PRs.

## Contributors
- [Leo Flapper](https://github.com/leoflapper)
- [All Contributors](../../contributors)

## License

[MIT](LICENSE) © Wefabric
