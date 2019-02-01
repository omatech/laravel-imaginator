<?php

namespace Omatech\Imaginator\Repositories;

use Omatech\Imaginator\Contracts\GetImageInterface;

class ImageExtractor implements GetImageInterface
{
    public function extract(string $path) : string
    {
        return $path;
    }
}
