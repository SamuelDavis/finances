<?php

namespace App;

use Illuminate\Support\Arr;

enum Header
{
    case Date;
    case Description;
    case Amount;

    /**
     * @return non-empty-string[]
     */
    public static function names(): array
    {
        return Arr::pluck(Header::cases(), 'name');
    }
}
