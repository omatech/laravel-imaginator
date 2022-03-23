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

    // Class of picture element
    'class' => 'image',

    // If exists loading attribute, add loading tag and value in picture element
    'loading' => 'lazy',
    
    // If exists width and height attributes they will be added at the end of the fallback img tag
    'width' => '165px',
    'height' => '165px',

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

## Example usage

```
<x-imaginator :formats="['webp', 'png', 'jpg']"
    :id="xxxxx" 
    :sets="[
        0 => [ 'srcset' => [55=>320, 60=>480, 65=>800, 70=>992] ]]" 
        class="pic"
    alt="Alt text" 
    data-toggle="modal"
    data-target="#modal-pack-xxxx" 
    loading="lazy"
    width="70px"
    height="70px"
/>
```


```
<x-imaginator
    :id="$project['img_project_portrait_imghash']"
    :formats="['webp', 'png', 'jpeg']"
    :sets="[
        0 => ['id' => $project['img_project_portrait_imghash']
        , 'media' => '(max-width: 767px)'
        ,'srcset' => [250=>320, 290=>375, 300=>390, 324=>414, 338=>428, 350=>440, 370=>460]],

        1 => ['id' => $project['img_project_portrait_imghash']
        , 'media' => '(min-width: 768px)'
        ,'srcset' => [274=>768, 282=>800, 290=>810, 300=>840, 333=>900, 363=>960, 380=>990, 390=>1024, 540=>1440]]
    ]"
    class="pic"
    loading="{{ $loop->index <= 1 ? 'auto' : 'lazy' }}"
    alt="{{$staticTexts->get('alt-project-image-prefix')}} {{$project['title']}}"
    width="550" 
    height="775"
></x-imaginator>
```

Generates this code:

```
<picture class="pic">
<source media='(max-width: 767px)' srcset='//www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=655b1ce46a10254d2d275b05179adff4&w=250&fm=webp 320w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=04872dfd321da47c45a03c09ad92eaa1&w=290&fm=webp 375w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=db004e9101f7c4664e8de0058931f19d&w=300&fm=webp 390w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=495578127c2e4ee5ef9b353e591b5b16&w=324&fm=webp 414w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=076c8626d5aa209fc9c3e9ec30887db7&w=338&fm=webp 428w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=985e42f242f83062424bd904113abd6d&w=350&fm=webp 440w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=0bcfc21d3eacfb863675187702f5080e&w=370&fm=webp 460w' type='image/webp'>
<source media='(min-width: 768px)' srcset='//www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=6e4d4cd53ed3eecbd89f95460a879c90&w=274&fm=webp 768w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=ce00e9558d9990daff55b7614df8911a&w=282&fm=webp 800w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=04872dfd321da47c45a03c09ad92eaa1&w=290&fm=webp 810w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=db004e9101f7c4664e8de0058931f19d&w=300&fm=webp 840w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=380146734c374f927d08d9bd94e08672&w=333&fm=webp 900w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=28c2b93b070cc30307f19e8594dd802b&w=363&fm=webp 960w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=dc577470f4294b58f72ddb4cbd609026&w=380&fm=webp 990w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=107f4862d6bdf451b946a89c57661a1f&w=390&fm=webp 1024w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=73a4c08a4ed8b1d976b6cf0be2dbe81c&w=540&fm=webp 1440w' type='image/webp'>
<source media='(max-width: 767px)' srcset='//www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=dd3a4ee1eb777f501ec0adfbe33ebbae&w=250&fm=png 320w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=bfaed6a7d0e3246e8dd2d7072f123c2b&w=290&fm=png 375w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=4cd2035f2634a05a18efd379f73eb2d7&w=300&fm=png 390w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=78c48f9ca55a511c934eb53fa92e3685&w=324&fm=png 414w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=2539bf39601363d9c8c36c4495559d04&w=338&fm=png 428w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=d7cf0ac4df602cebfe02499533514ae8&w=350&fm=png 440w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=288d0b82b67affbc25f6ad1a65136bbe&w=370&fm=png 460w' type='image/png'>
<source media='(min-width: 768px)' srcset='//www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=b4d80b69af2ae0e8a5047a52ba0ab61b&w=274&fm=png 768w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=5ff10c23e89027d70b4a7859ef298b73&w=282&fm=png 800w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=bfaed6a7d0e3246e8dd2d7072f123c2b&w=290&fm=png 810w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=4cd2035f2634a05a18efd379f73eb2d7&w=300&fm=png 840w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=3973f2a2e944207f41458e1a09181736&w=333&fm=png 900w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=2896b9d2377b699061df3ceb013f5fe6&w=363&fm=png 960w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=bcd82e220582ca697dafdb701cf56fc4&w=380&fm=png 990w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=08b1764250704d5871dd503599e7a52e&w=390&fm=png 1024w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=81caa35c82eccd9f4ec07e9a81d5185c&w=540&fm=png 1440w' type='image/png'>
<source media='(max-width: 767px)' srcset='//www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=dd3a4ee1eb777f501ec0adfbe33ebbae&w=250&fm=png 320w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=bfaed6a7d0e3246e8dd2d7072f123c2b&w=290&fm=png 375w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=4cd2035f2634a05a18efd379f73eb2d7&w=300&fm=png 390w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=78c48f9ca55a511c934eb53fa92e3685&w=324&fm=png 414w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=2539bf39601363d9c8c36c4495559d04&w=338&fm=png 428w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=d7cf0ac4df602cebfe02499533514ae8&w=350&fm=png 440w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=288d0b82b67affbc25f6ad1a65136bbe&w=370&fm=png 460w' type='image/png'>
<source media='(min-width: 768px)' srcset='//www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=b4d80b69af2ae0e8a5047a52ba0ab61b&w=274&fm=png 768w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=5ff10c23e89027d70b4a7859ef298b73&w=282&fm=png 800w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=bfaed6a7d0e3246e8dd2d7072f123c2b&w=290&fm=png 810w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=4cd2035f2634a05a18efd379f73eb2d7&w=300&fm=png 840w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=3973f2a2e944207f41458e1a09181736&w=333&fm=png 900w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=2896b9d2377b699061df3ceb013f5fe6&w=363&fm=png 960w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=bcd82e220582ca697dafdb701cf56fc4&w=380&fm=png 990w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=08b1764250704d5871dd503599e7a52e&w=390&fm=png 1024w, //www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=81caa35c82eccd9f4ec07e9a81d5185c&w=540&fm=png 1440w' type='image/png'>
<img src='//www.omatech.com/img/8669736a633df078aff796a6fd0dcf68_5155?s=52b3c1030cd031367f03dde575118928&fm=png' alt='Imagen del proyecto Teatre Lliure' loading=auto  width="550"  height="775" >
</picture>
```


## Organization

* [Omatech](https://www.omatech.com)

## Contributors

See the contributors list [here](https://github.com/omatech/laravel-imaginator/graphs/contributors).

## License
[MIT license](http://opensource.org/licenses/MIT).
