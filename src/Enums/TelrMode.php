<?php

namespace AhmedTaha\Telr\Enums;

enum TelrMode : string
{
    case TEST = 'test';
    case LIVE = 'live';

    public function isTest(): bool
    {
        return $this === self::TEST;
    }

    public function isLive(): bool
    {
        return $this === self::LIVE;
    }
}
