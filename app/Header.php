<?php

namespace App;

use Illuminate\Support\Arr;

enum Header
{
    case Date;
    case Description;
    case Amount;

    public static function names(): array
    {
        return Arr::pluck(Header::cases(), 'name');
    }
}
