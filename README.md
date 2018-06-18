# genderguess
Guessing User's gender with the gender API

### PHP Requirement
php ^7.1.3

### Installation

Set relevant app configs such
- DB Credentials
- GendAPI Key
- App Url, & etc

via .env file - you can use .env.example as a guide

You can quickly get setup with php built-in-web-server as suggested below 
or configure the app on your own stack

```sh
git clone https://github.com/fiifiappiah/genderguess.git
cd genderguess
composer install
cd public
php -S localhost:8001 
php artisan migrate:refresh
```
Go to localhost:8001


