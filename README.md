
# Emandii

A B2B platform to facilitate retailers and wholsellers to connect and manage their demands.


## Acknowledgements

 - [Laravel jetstream](https://jetstream.laravel.com/2.x/introduction.html)
 - [Spatie Laravel-permission](https://spatie.be/docs/laravel-permission/v5/introduction)
 - [Rinvex Subscriptions](https://github.com/rinvex/laravel-subscriptions)
 - [Promocodes](https://github.com/zgabievi/laravel-promocodes)
 - [Omnipay for Laravel](https://github.com/barryvdh/laravel-omnipay)
 - [Sentry for Laravel](https://github.com/getsentry/sentry-laravel)

  
## Authors

- [Shahbaz Mahmood Khan](https://github.com/smkhan321)
- [Abdul Ahad Mirza](https://github.com/mirza4027)
- [Abdul Rauf](https://github.com/AliasKingsWorth)

  
## Pre Requisites:
    PHP 7.4.*
    Mysql 8*
    Node >= 14
    Composer
    Yarn
    

## Run Locally

Clone the project

```bash
  git clone https://github.com/Cbeyond-Hospitality/emandii.git
```

Go to the project directory

```bash
  cd emandii
```

Create .env file from .env.example and replace values according to your local configs

```bash
  cp .env.example .env
```

Update Config Cache

```bash
  php artisan config:cache
```

Install Dependencies

```bash
  composer install
```

Once Dependencies resolved, run following commands in sequence

```bash
  php artisan migrate:fresh --path=database/migrations/rinvex/*
  php artisan migrate --seed
  php artisan migrate --path=database/migrations/stats
```
Install Frontend Dependencies

```bash
  yarn install
```

Make development build

```bash
  yarn run dev
```

Start Server

```bash
  php artisan serve
```

#### All set, visit the link displayed in your console.
## Running Tests

To run tests, run the following command

```bash
  php artisan test
```

  
## Feedback

If you have any feedback, please reach out to us at info@cbeyondint.com
# Alhdeen
# Alahdeen-B2b
# Alahdeen-B2b
# Alahdeen-B2b
# Alahdeen-B2b
# AlahdeenB2b
