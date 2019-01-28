<?php

namespace Omatech\Imaginator\Repositories;

use League\Glide\Signatures\Signature;

class Imaginator
{
    private $signature;
    private $baseUrl;

    public function __construct()
    {
        $this->signature = new Signature(config('imaginator.key'));
        $this->baseUrl = $this->getBaseUrl();
    }
    
    public function generateUrls($data)
    {
        $srcset = '';
        $sizes = $data['srcset'] ?? [];
        $options = $data['options'] ?? [];
        $format = $data['format'] ?? [];

        $format = $this->supportedFormat($format);

        foreach ($sizes as $size) {
            $srcset .= $this->prepareUri($data['hash'], $format, $options, $size);
        }

        if (empty($srcset)) {
            $srcset .= $this->prepareUri($data['hash'], $format, $options);
        } else {
            $srcset = substr($srcset, 0, -2);
        }

        return [
            'base'  =>  $this->prepareUri($data['hash'], 'png', $options),
            'srcset' => $srcset,
            'format' => $format
        ];
    }


    private function getBaseUrl()
    {
        $scheme = config('imaginator.scheme');
        $url = config('imaginator.server');
        $prefix = config('imaginator.url_prefix');

        if (in_array($scheme, ['http', 'https'])) {
            $http = $scheme.'://';
        } else {
            $http = $scheme;
        }

        if (empty($url)) {
            $domain = parse_url(url()->current());
            $http .= $domain['host'].':'.$domain['port'];
        } else {
            $http .= $url;
        }

        if (!empty($prefix)) {
            $http .= '/'.$prefix;
        }

        $http .= '/';

        return $http;
    }

    private function prepareUri($hash, $format, $options = [], $size = null)
    {
        $sig = $this->signature->generateSignature($hash, array_merge(['w' => $size, 'fm' => $format], $options));
        
        $uri = $this->baseUrl.$hash.'?'.http_build_query(array_merge($options, [
            's' => $sig,
            'w' => $size,
            'fm' => $format
        ]));

        if ($size) {
            $uri .= " {$size}w, ";
        }

        return $uri;
    }


    private function supportedFormat($format)
    {
        $formats = ['webp', 'png', 'jpg', 'gif'];

        if (in_array($format, $formats)) {
            return $format;
        }

        return 'png';
    }
}
