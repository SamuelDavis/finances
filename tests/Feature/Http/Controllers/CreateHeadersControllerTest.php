<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class CreateHeadersControllerTest extends TestCase
{
    use AssertRedirect;

    private string $fromRoute = "headers";
}
