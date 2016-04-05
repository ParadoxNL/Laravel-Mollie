<?php

namespace ParadoxNL\Mollie\Facades;

use Illuminate\Support\Facades\Facade;

class Mollie extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \ParadoxNL\Mollie\Mollie::class;
    }
}