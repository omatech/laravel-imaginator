<?php

namespace Omatech\Imaginator\Contracts;

interface GetImageInterface
{
    public function extract(string $hash) : string;
}
