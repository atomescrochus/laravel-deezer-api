<?php

namespace Atomescrochus\Deezer\Exceptions;

use Exception;

class Unsupported extends Exception
{
    public static function needsOAuth()
    {
        return new static('This search type needs an OAuth authentication that is not supported (yet) by this package.');
    }
}
