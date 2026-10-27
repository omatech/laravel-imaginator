<?php

namespace Omatech\Imaginator\Responses;

use League\Flysystem\FilesystemOperator;
use League\Glide\Responses\ResponseFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Symfony response for Glide 2 to 4, from league/glide-symfony 2.1 (MIT, Jonathan Reinink),
 * which stops at Glide 3.
 */
class SymfonyResponseFactory implements ResponseFactoryInterface
{
    protected $request;

    public function __construct(?Request $request = null)
    {
        $this->request = $request;
    }

    public function create(FilesystemOperator $cache, $path)
    {
        $stream = $cache->readStream($path);

        $response = new StreamedResponse();
        $response->headers->set('Content-Type', $cache->mimeType($path));
        $response->headers->set('Content-Length', $cache->fileSize($path));
        $response->setPublic();
        $response->setMaxAge(31536000);
        $response->setExpires(date_create()->modify('+1 years'));

        if ($this->request) {
            $response->setLastModified(date_create()->setTimestamp($cache->lastModified($path)));
            $response->isNotModified($this->request);
        }

        $response->setCallback(function () use ($stream) {
            if (ftell($stream) !== 0) {
                rewind($stream);
            }
            fpassthru($stream);
            fclose($stream);
        });

        return $response;
    }
}
