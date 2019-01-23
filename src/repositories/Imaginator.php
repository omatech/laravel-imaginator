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
        $sizes = $data['srcset'];
        $format = $this->supportedFormat($data['format']);

        foreach ($sizes as $size) {
            $srcset .= $this->prepareUri($data['hash'], $data['format'], $size);
        }

        if (empty($srcset)) {
            $srcset .= $this->prepareUri($data['hash'], $data['format']);
        } else {
            $srcset = substr($srcset, 0, -2);
        }

        return [
            'base'  =>  $this->prepareUri($data['hash'], 'png'),
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

        $http .= config('imaginator.server');

        if (!empty($prefix)) {
            $http .= '/'.$prefix;
        }

        $http .= '/';

        return $http;
    }

    private function prepareUri($hash, $format, $size = null)
    {
        $sig = $this->signature->generateSignature($hash, ['w' => $size, 'fm' => $format]);
        
        $uri = $this->baseUrl.$hash.'?'.http_build_query([
            's' => $sig,
            'w' => $size,
            'fm' => $format
        ]);

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
