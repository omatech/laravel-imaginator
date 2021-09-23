<?php

return [
    'scheme'                 => '//',      // http, https, //
    'server'                 => '',        // images.domain.com, domain.com
    'url_prefix'             => 'img',     // img folder => domain.com/img
    'key'                    => '',        // hash key
    'glide_security_enabled' => true,      // enable glide security
    'loaders' => [
        'local' => [
            'source_disk'     => 'local',  // filesystem disk
            'cache_disk'      => 'local',  // filesystem disk
            'get_image_class' => 'Omatech\Imaginator\Repositories\ImageExtractor',  // class to get the image
        ]
    ]
];
