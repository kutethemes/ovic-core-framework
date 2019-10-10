# Ovic-Laravel-Framework

## Install

To install through Composer, by run the following command:

``` bash
composer require ovic-core/framework
```

The package will automatically register a service provider and alias.

Optionally, publish the package's configuration file by running:

``` bash
All     : php artisan vendor:publish --provider="Ovic\Framework\FrameworkServiceProvider"
Assets  : php artisan vendor:publish --tag=ovic-assets --force
Views   : php artisan vendor:publish --tag=ovic-views --force
Config  : php artisan vendor:publish --tag=ovic-config --force
```

**Tip: don't forget to run `composer dump-autoload` afterwards.**

## Documentation

You'll find installation instructions and full documentation on [https://kutethemes.com/](https://kutethemes.com/).

## About Kutethemes

Kutethemes is a freelance web developer specialising on the Laravel framework. View all my packages [on my website](https://kutethemes.com/).


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
