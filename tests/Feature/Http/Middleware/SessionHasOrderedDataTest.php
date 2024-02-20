<?php

namespace Tests\Feature\Http\Middleware;

use Tests\TestCase;

class SessionHasOrderedDataTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->markTestSkipped('tbd');
        $response = $this->get('/'); /** @phpstan-ignore-line  */
        $response->assertStatus(200);
    }
}
