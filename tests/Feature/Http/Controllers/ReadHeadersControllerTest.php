<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ReadHeadersControllerTest extends TestCase
{
    use AssertRedirect;

    private string $fromRoute = "headers";

    public function test_renders()
    {
        $data = [["a", "b", "c"], ["1", "2", "3"], ["4", "5", "6"]];
        Session::put("data", $data);
        $this->get(route("headers"))
            ->assertStatus(Response::HTTP_OK)
            ->assertSeeInOrder(array_merge(...$data));
    }
}
