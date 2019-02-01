<?php

namespace Omatech\Imaginator\Controllers;

use App\Http\Controllers\Controller;
use Omatech\Imaginator\Repositories\Imaginator;

class ImaginatorController extends Controller
{
    public function get(Imaginator $imaginator, $path)
    {
        return $imaginator->getProcessedImage($path, request()->all());
    }
}
