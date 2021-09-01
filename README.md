# Gym Manager
A simple gym manager.

## Technologies
PHP 8, Symfony 5.3, API Platform, PHPUnit

## Set up the project
```
composer install
```
```
php bin/console doctrine:migration:migrate
```
### Create admin
```
php bin/console user:create-admin admin@exampl.com password first-name last-name
```

### Generate the SSL keys:
```
mkdir -p config/jwt && openssl rand -hex 64 > config/jwt/jwt_passphrase.txt
```
```
php bin/console lexik:jwt:generate-keypair
```
### Running unit tests
```
php bin/phpunit
```