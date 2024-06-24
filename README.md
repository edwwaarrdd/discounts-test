## Requirements
* **PHP 8.3**

or

* **Docker**

## Installation
### Docker
```bash
docker-compose up -d
docker-compose exec slim composer install
```
### PHP
Make sure composer and php 8.3 are installed on your machine.
```bash
composer install
composer start #to start the server
```

## Usage
You can post the order json to the following endpoint:
http://localhost:8080/discounts/calculate

To run the complete test suite:
```bash
composer test:all
or
docker-compose exec slim composer test:all
```
This runs:
- php-cs-fixer
- PHP_CodeSniffer
- phpstan
- phpunit
