<?php

namespace Tests\Feature\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ReadTableControllerTest extends TestCase
{
    use AssertRedirect;

    protected function getFromRoute(): string
    {
        return 'table';
    }

    public function test_renders_table(): void
    {
        $data = [['a', 'b', 'c'], ['1', '2', '3'], ['4', '5', '6']];
        $response = $this->withSession(compact('data'))->get(route('table'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHasAll(['headers', 'rows']);
        $response->assertSeeInOrder(array_merge(...$data));
    }
}
