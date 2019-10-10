# Ovic-Laravel-Framework

## Install

To install through Composer, by run the following command:

``` bash
composer require ovic-core/framework
```

The package will automatically register a service provider and alias.

Optionally, publish the package's configuration file by running:

``` bash
php artisan vendor:publish --provider="Ovic\Framework\FrameworkServiceProvider"
```

### Autoloading

By default the module classes are not loaded automatically. You can autoload your modules using `psr-4`. For example:

``` json
{
  "autoload": {
    "psr-4": {
      "Ovic\\Framework\\": "src/"
    },
    "files": [
        "src/helpers.php"
    ]
  }
}
```

**Tip: don't forget to run `composer dump-autoload` afterwards.**

## Documentation

You'll find installation instructions and full documentation on [https://kutethemes.com/](https://kutethemes.com/).

## About Kutethemes

Kutethemes is a freelance web developer specialising on the Laravel framework. View all my packages [on my website](https://kutethemes.com/).


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
