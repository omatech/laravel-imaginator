<?php

namespace Omatech\Imaginator\Controllers;

use Illuminate\Routing\Controller;
use Omatech\Imaginator\Repositories\Imaginator;

class ImaginatorController extends Controller
{
    public function get(Imaginator $imaginator, $path)
    {
        session_write_close();
        session_cache_limiter('public');
        session_start();
        header_remove( 'Pragma' );

        return $imaginator->getProcessedImage($path, request()->all());
    }
}
