<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class DeleteUploadControllerTest extends TestCase
{
    public function test_clears_session_data(): void
    {
        $this->withSession(['data' => 'testing'])
            ->delete(route('upload'))
            ->assertSessionMissing('data');
    }
}
