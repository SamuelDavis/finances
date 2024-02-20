<?php

namespace Tests\Feature\Http\Controllers;

use App\Header;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CreateHeadersControllerTest extends TestCase
{
    use AssertRedirect;

    public function test_orders_data(): void
    {
        $data = [['a', 'b', 'c', 'd'], [1, 2, 3, 4]];
        $this->withSession(compact('data'))->post(route('headers'), [
            'headers' => [
                'a' => Header::Amount->name,
                'b' => Header::Date->name,
                'c' => Header::Description->name,
            ],
        ]);

        $expected = [
            [
                Header::Date->name,
                Header::Description->name,
                Header::Amount->name,
            ],
            [2, 3, 1],
        ];
        $this->assertSame($expected, Session::get('data'));
    }

    protected function getFromRoute(): string
    {
        return 'headers';
    }
}
