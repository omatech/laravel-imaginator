<?php

namespace Omatech\Imaginator\Controllers;

use League\Glide\ServerFactory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\Signatures\SignatureException;
use League\Glide\Responses\LaravelResponseFactory;
use Omatech\Imaginator\Contracts\GetImageInterface;
use Omatech\Imaginator\Exceptions\SecurityException;

class ImaginatorController extends Controller
{
    public function get(GetImageInterface $image, $path)
    {
        try {
            // Set complicated sign key
            $signkey = config('imaginator.key');

            // Validate HTTP signature
            SignatureFactory::create($signkey)->validateRequest($path, request()->all());

            $image = $image->extract($path);

            $server = ServerFactory::create([
                'response' => new LaravelResponseFactory(app('request')),
                'source' => Storage::disk(config('imaginator.source_disk'))->getDriver(),
                'cache' => Storage::disk(config('imaginator.cache_disk'))->getDriver(),
                'cache_path_prefix' => '.cache'
            ]);

            return $server->getImageResponse($image, request()->all());
        } catch (SignatureException $e) {
            throw new SecurityException('Invalid token');
        }
    }
}
