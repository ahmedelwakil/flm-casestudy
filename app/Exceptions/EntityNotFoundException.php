<?php

namespace App\Exceptions;

class EntityNotFoundException extends \Exception
{
    /**
     * EntityNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('Entity Not Found');
    }
}
