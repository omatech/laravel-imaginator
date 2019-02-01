<?php

namespace Omatech\Imaginator\Repositories;

use League\Glide\Signatures\Signature;
use Omatech\Imaginator\Contracts\GetImageInterface;
use Illuminate\Support\Facades\Storage;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;

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

        if (!$this->isAssoc($sizes)) {
            $sizes = array_combine($sizes, $sizes);
        }
        
        foreach ($sizes as $size => $width) {
            $srcset .= $this->prepareUri($data['hash'], $format, $options, [$size => $width]);
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
            $url = parse_url(url()->current());
            $host = $url['host'];
            $port = (isset($url['port'])) ? ':'.$url['port'] : '';
            $http .= $host.$port;
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
        if ($size) {
            $width = key($size);
            $size = $size[key($size)];
        } else {
            $width = null;
            $size = null;
        }

        $sig = $this->signature->generateSignature($hash, array_merge(['w' => $width, 'fm' => $format], $options));
        
        $uri = $this->baseUrl.$hash.'?'.http_build_query(array_merge($options, [
            's' => $sig,
            'w' => $width,
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

    private function isAssoc(array $arr)
    {
        if (array() === $arr) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function getProcessedImage($path, $params)
    {
        $image = app()->make(GetImageInterface::class);
        $image = $image->extract($path);

        $server = ServerFactory::create([
            'response'   => new LaravelResponseFactory(app('request')),
            'source'     => Storage::disk(config('imaginator.source_disk'))->getDriver(),
            'cache'      => Storage::disk(config('imaginator.cache_disk'))->getDriver(),
            'watermarks' => Storage::disk(config('imaginator.cache_disk'))->getDriver(),
            'watermarks_path_prefix' => '.watermarks',
            'cache_path_prefix'      => '.cache'
        ]);

        if (isset($params['debug']) && $params['debug'] == true) {
            $params = $this->processDebug($params);
            $server->deleteCache($image);
        }

        return $server->getImageResponse($image, $params);
    }

    private function processDebug($params)
    {
        return $this->generateDebugWatermark($params);
    }

    private function generateDebugWatermark($params)
    {
        $width = $params['w'] ?? 'original';
        $format = $params['fm'] ?? 'png';
        $fileName = $width.'_'.$format.'.png';
        $params['mark'] = $fileName;

        $imgWidth = 150;
        $imgHeight = 27;
        $fontSize = 5;
        $text = "$width - $format";

        //center
        $xPosition = (($imgWidth/2)-((imagefontwidth($fontSize)*strlen($text))/2));
        $yPosition = (($imgHeight/2)-(imagefontheight($fontSize)/2));

        $newImg = imagecreate($imgWidth, $imgHeight);
        $bgColor = imagecolorallocate($newImg, 0, 0, 0);
        $fontColor = imagecolorallocate($newImg, 255, 0, 0);
        imagestring($newImg, $fontSize, $xPosition, $yPosition, $text, $fontColor);

        ob_start();
        imagepng($newImg);
        $image = ob_get_contents();
        ob_end_clean();

        Storage::disk(config('imaginator.cache_disk'))->put('.watermarks/'.$fileName, $image);
        imagedestroy($newImg);

        return $params;
    }
}
