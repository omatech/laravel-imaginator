<?php

namespace Omatech\Imaginator\Controllers;

use League\Glide\ServerFactory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use League\Glide\Responses\LaravelResponseFactory;
use Omatech\Imaginator\Contracts\GetImageInterface;
use Omatech\Imaginator\Exceptions\SecurityException;

class ImaginatorController extends Controller
{
    public function get(GetImageInterface $image, $path)
    {
        $params = request()->all();
        
        $image = $image->extract($path);

        $server = ServerFactory::create([
            'response' => new LaravelResponseFactory(app('request')),
            'source' => Storage::disk(config('imaginator.source_disk'))->getDriver(),
            'cache' => Storage::disk(config('imaginator.cache_disk'))->getDriver(),
            'watermarks' => Storage::disk(config('imaginator.source_disk'))->getDriver(),
            'watermarks_path_prefix' => 'images/watermarks',
            'cache_path_prefix' => '.cache'
        ]);

        if (isset($params['debug']) && $params['debug']  == true) {
            $params['mark'] = 'hola.png';
            //$params['markw'] = 250;

            $width = $params['w'] ?? 'original';
            $format = $params['fm'] ?? 'png';


            $this->genWatermark($width, $format);
            $server->deleteCache($path);
        }

        return $server->getImageResponse($image, $params);
    }

    private function genWatermark($width, $format)
    {
        //set the desired width and height
        $imgWidth = 150;
        $imgHeight = 27;
        $fontSize = 5;

        $text = "$width - $format";

        $xPosition = (($imgWidth/2)-((imagefontwidth($fontSize)*strlen($text))/2));
        $yPosition = (($imgHeight/2)-(imagefontheight($fontSize)/2));

        //create a new palette based image
        $newImg = imagecreate($imgWidth, $imgHeight);

        //set the image background color to black
        $bgColor = imagecolorallocate($newImg, 0, 0, 0);
        //set the font color to red
        $fontColor = imagecolorallocate($newImg, 255, 0, 0);

        //write the text on the created image
        imagestring($newImg, $fontSize, $xPosition, $yPosition, $text, $fontColor);
        imagepng($newImg, storage_path('app/images/watermarks/hola.png'));
        imagedestroy($newImg);
    }
}
