<?php

namespace Atomescrochus\Deezer\Exceptions;

use Exception;

class UsageErrors extends Exception
{
    public static function searchType()
    {
        return new static('This search type is invalid.');
    }

    public static function advancedSearchMissingTerms()
    {
        return new static('You need to set something to search first.');
    }
}
