# Ovic Laravel Framework

[![Latest Stable Version](https://poser.pugx.org/ovic-core/framework/v/stable?format=flat-square)](https://packagist.org/packages/ovic-core/framework)
[![Total Downloads](https://poser.pugx.org/ovic-core/framework/downloads?format=flat-square)](https://packagist.org/packages/ovic-core/framework)
[![Latest Unstable Version](https://poser.pugx.org/ovic-core/framework/v/unstable?format=flat-square)](https://packagist.org/packages/ovic-core/framework)
[![License](https://poser.pugx.org/ovic-core/framework/license?format=flat-square)](https://packagist.org/packages/ovic-core/framework)

## Install

To install through Composer, by run the following command:

``` bash
composer require ovic-core/framework
```

To install nodejs, bootstrap, by run the following command:

Require:

- Nodejs: https://nodejs.org/
- Bootstrap: https://getbootstrap.com/

``` bash
npm install
npm install bootstrap
npm run dev
```

To create Auth, by run the following command ( https://laravel.com/docs/6.x/authentication ):

``` bash
composer require laravel/ui ( if do not exits )
php artisan ui vue --auth
```

To create migrate, by run the following command:

``` bash
All         : php artisan migrate
Only vendor : php artisan migrate --path=\vendor\ovic-core\framework\database
```

To create "super admin" user, by run the following command:

``` bash
php artisan db:seed --class=UsersTableSeeder

username    : Super Admin
user        : admin@laravel.com
pass        : 12345678
```

The package will automatically register a service provider and alias.

Optionally, publish the package's configuration file by running:

``` bash
php artisan vendor:publish --tag=ovic-auth --force
```

## Advance options

To update through Composer, by run the following command:

``` bash
composer update ovic-core/framework
```

To remove through Composer, by run the following command:

``` bash
composer remove ovic-core/framework
```

To clear cache Composer, by run the following command:

``` bash
composer clear-cache
```

To publish the modules file by running:

``` bash
php artisan module:publish
```

To publish the package's file by running:

``` bash
All     : php artisan vendor:publish --provider="Ovic\Framework\FrameworkServiceProvider" --force
Config  : php artisan vendor:publish --tag=ovic-config --force
Assets  : php artisan vendor:publish --tag=ovic-assets --force
Views   : php artisan vendor:publish --tag=ovic-views --force
Lang    : php artisan vendor:publish --tag=ovic-lang --force
Auth    : php artisan vendor:publish --tag=ovic-auth --force
```

To registering HTTP Session, by run the following command ( https://laravel.com/docs/6.x/session ):

``` bash
php artisan session:table
```

To registering Events & Listeners, by run the following command ( https://laravel.com/docs/6.x/events ):

``` bash
php artisan event:generate
```

**Tip: don't forget to run `composer dump-autoload` afterwards.**

## Documentation

You'll find installation instructions and full documentation on [https://kutethemes.com/](https://kutethemes.com/).

## About Kutethemes

Kutethemes is a freelance web developer specialising on the Laravel framework. View all my packages [on my website](https://kutethemes.com/).


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
