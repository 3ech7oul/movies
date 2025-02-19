<?php

namespace App\Http\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class RatingException extends HttpException
{
    public function __construct(array $allow, string $message = '', ?\Throwable $previous = null, int $code = 0, array $headers = [])
    {
        $headers['Allow'] = strtoupper(implode(', ', $allow));

        parent::__construct($code, $message, $previous, $headers, $code);
    }
}
