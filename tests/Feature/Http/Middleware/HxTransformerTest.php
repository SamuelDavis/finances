<?php

namespace Tests\Feature\Http\Middleware;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class HxTransformerTest extends TestCase
{
    public function test_hx_redirect_headers(): void
    {
        $file = UploadedFile::fake()->create('file', 2048, 'text/csv');
        $this->withHeader('Hx-Request', 'true')
            ->post(route('upload'), compact('file'))
            ->assertHeaderMissing('Location')
            ->assertHeader('Hx-Redirect', route('headers'));
    }
}
