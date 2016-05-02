<?php

namespace Din\DinImage\Facades;

use Illuminate\Support\Facades\Facade;

class DinImage extends Facade
{
    /**
     * Name of the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'dinimage';
    }
}