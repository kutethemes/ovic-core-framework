# Ovic-Laravel-Framework

## Install

To install through Composer, by run the following command:

``` bash
composer require ovic-core/framework
```

To create Auth, by run the following command:

``` bash
php artisan ui vue --auth
```

To install nodejs, bootstrap, by run the following command ( require nodejs: https://nodejs.org/):

``` bash
npm install
npm install bootstrap
npm run dev
```

To create migrate, by run the following command:

``` bash
All         : php artisan migrate
Only vendor : php artisan migrate --path=\vendor\ovic-core\framework\database
```

The package will automatically register a service provider and alias.

Optionally, publish the package's configuration file by running:

``` bash
All     : php artisan vendor:publish --provider="Ovic\Framework\FrameworkServiceProvider" --force
Assets  : php artisan vendor:publish --tag=ovic-assets --force
Views   : php artisan vendor:publish --tag=ovic-views --force
Lang    : php artisan vendor:publish --tag=ovic-lang --force
Auth    : php artisan vendor:publish --tag=ovic-auth --force
```

## Config

To update through Composer, by run the following command:

``` bash
composer update ovic-core/framework
```

**Tip: don't forget to run `composer dump-autoload` afterwards.**

## Documentation

You'll find installation instructions and full documentation on [https://kutethemes.com/](https://kutethemes.com/).

## About Kutethemes

Kutethemes is a freelance web developer specialising on the Laravel framework. View all my packages [on my website](https://kutethemes.com/).


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
