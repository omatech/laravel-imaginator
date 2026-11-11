<?php

namespace Omatech\Imaginator\Exceptions;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/** A missing or forged Glide signature is the client's error: 403, and Laravel does not report HTTP exceptions. */
class SecurityException extends AccessDeniedHttpException
{
}
