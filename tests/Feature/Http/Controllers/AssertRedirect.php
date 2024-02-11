<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Testing\TestResponse;

/**
 * @property string $fromRoute
 */
trait AssertRedirect
{
    private string $toRoute = "upload";

    public function test_missing_data_redirects(): void
    {
        $this->get(route($this->fromRoute))->assertRedirectToRoute(
            $this->toRoute,
        );
    }

    /**
     * @param string $uri
     * @param array $headers
     * @return TestResponse
     */
    abstract public function get($uri, array $headers = []);
}
