<?php

namespace Quote\Laravel\Facade;

use Illuminate\Support\Facades\Facade;

class Quote extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'quote';
    }
}
