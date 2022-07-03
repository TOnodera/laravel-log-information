<?php declare(strict_types=1);
namespace Tonod\LogInformation\Exceptions;

use Exception;

class ApplicationException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
