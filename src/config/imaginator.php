<?php

return [
    'scheme'                 => '',       // http, https, //
    'server'                 => '',       // images.domain.com, domain.com
    'url_prefix'             => '',       // img folder => domain.com/img
    'source_disk'            => 'local',  // filesystem disk
    'cache_disk'             => 'local',  // filesystem disk
    'key'                    => '',       // hash key
    'get_image_class'        => 'Omatech\Imaginator\Repositories\ImageExtractor',       // class to get the image
    'glide_security_enabled' => true      // enable glide security
];
