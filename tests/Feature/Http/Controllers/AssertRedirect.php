<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Testing\TestResponse;

trait AssertRedirect
{
    public function test_missing_data_redirects(): void
    {
        $this->get(route($this->getFromRoute()))->assertRedirectToRoute(
            $this->getToRoute(),
        );
    }

    /**
     * @param  string  $uri
     * @return TestResponse
     */
    abstract public function get($uri, array $headers = []);

    /**
     * @return non-empty-string
     */
    abstract protected function getFromRoute(): string;

    /**
     * @return non-empty-string
     */
    protected function getToRoute(): string
    {
        return 'upload';
    }
}
