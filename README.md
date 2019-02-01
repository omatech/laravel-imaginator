# Laravel: Responsive Image Creator

Laravel Imaginator helps you to create a HTML Picture Tag, with an image in all sizes you need.

## Installation

Require the package in your composer.json.

```
composer require omatech/laravel-imaginator
```

Publish the configuration.

```
php artisan vendor:publish --provider=Omatech\Imaginator\Providers\ConfigurationServiceProvider
```

## Setup configuration

Publish the configuration.

```
'scheme'     => 'https' 
'server'     => 'images.domain.com'
'url_prefix' => 'uploads'
```

Configure the url of the image, you can configure the current domain, in subdomain or/with subfolder.

The example will generate https://images.domain.com/uploads/

```
'source_disk' => 'local'
'cache_disk'  => 'local'
```

Choose the disk from filesystem.

```
'key'                    => ''
'glide_security_enabled' => true
```

The `key` with the `glide_security_enable` will protect the url from changes.

```
'get_image_class' => 'Omatech\Imaginator\Repositories\ImageExtractor'
```

We will need a class with the method `extract` to get the image path. You can override it with your own class, it must implements the `Omatech\Imaginator\Contracts\GetImageInterface` interface.

## Using

```
@imaginator([
    // The filename relative to filesystem.
    'id' => 'picture.png',

    // Alt of the image.
    'alt' => 'alt',

    // All the formats in which to generate the image.
    'formats' => ['webp','png','jpg'],

    // Glide options. http://glide.thephpleague.com/1.0/api/quick-reference/
    // Debug option will show the information of the generated image, the format and width.
    'options' => ['filt' => 'greyscale', 'debug' => true],

    // Sets of medias and sizes for your images, image size - width size.
    'sets' => [
        0 => [
            'media' => '(max-width: 600px)',
            'srcset' => [200 => 400, 300 => 600, 400 => 800, 500 => 1000]
        ],
        1 => [
            'media' => '(min-width: 600px) and (max-width: 1800px)',
            'srcset' => 200
        ]
    ]])
@endimaginator
```

All you need to do is call the component `@imaginator` from a Laravel Blade.

## Organization

* [Omatech](https://www.omatech.com)

## Contributors

See the contributors list [here](https://github.com/omatech/laravel-imaginator/graphs/contributors).

## License
[MIT license](http://opensource.org/licenses/MIT).
