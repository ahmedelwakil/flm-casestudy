<?php

namespace App\Exceptions;

class UnauthorizedAction extends \Exception
{
    /**
     * UnauthorizedAction constructor.
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? "Unauthorized");
    }
}
