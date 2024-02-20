<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

trait InteractsWithSessionData
{
    /**
     * @return string[][]
     */
    protected function getSessionData(): array
    {
        $data = Session::get('data', []);
        assert(is_array($data));
        foreach ($data as $datum) {
            assert(is_array($datum));
        }

        return $data;
    }
}
