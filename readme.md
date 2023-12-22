# Segun Laravel Superban Package

## Introduction

The Laravel Superban package provides a middleware for rate limiting and banning clients based on specified criteria. It allows you to easily configure rate limits, ban durations, and apply these restrictions to specific or all routes in your Laravel application.

## Installation

Install the package via Composer:

```bash
composer require segun/superban
```

## Configuration
Publish the configuration file to customize your settings

```bash
php artisan vendor:publish --tag=superban-config
```
The configuration file will be located at `config/superban.php`.

## Configuration Options
cache_driver: Specify the cache driver for rate limiting and banning (e.g redis, database).
ban_criteria: Define criteria for banning clients (e.g user_id, ip_address, email).

## Usage
Apply the superban middleware to your routes or route groups:
```bash
use Superban\Middleware\SuperbanMiddleware;

Route::middleware(['superban:200,2,1440'])->group(function () {
    Route::post('/thisroute', function () {
        // Your route logic here
    });

    Route::post('/anotherroute', function () {
        // Your route logic here
    });
});
```
In this example:

200 is the number of requests allowed.
2 is the time window in minutes for the specified number of requests.
1440 is the ban duration in minutes.

