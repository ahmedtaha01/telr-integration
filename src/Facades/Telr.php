<?php

namespace AhmedTaha\Telr\Facades;

use AhmedTaha\Telr\TelrService;
use Illuminate\Support\Facades\Facade;

class Telr extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TelrService::class;
    }
}