# Gym Manager
Simple gym manager with DDD architecture.
## Technologies
PHP 8, Symfony 5.3, API Platform, PHPUnit
### Generate the SSL keys:
`mkdir -p config/jwt && openssl rand -hex 64 > config/jwt/jwt_passphrase.txt`  
`php bin/console lexik:jwt:generate-keypair`  
