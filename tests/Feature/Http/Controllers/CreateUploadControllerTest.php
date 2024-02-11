<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateUploadControllerTest extends TestCase
{
    public function test_mime_type_validation(): void
    {
        $file = UploadedFile::fake()->create("test_file", 2048, "text/plain");

        $response = $this->post(route("upload"), compact("file"));

        /** @var MessageBag $errors */
        $errors = View::shared("errors");
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertArrayHasKey("file", $errors->getMessages());
        $response->assertViewHas("errors");
    }

    public function test_stores_csv_in_session(): void
    {
        $stream = tmpfile();
        $data = [["a", "b", "c"], ["1", "2", "3"], ["4", "5", "6"]];
        foreach ($data as $row) {
            fputcsv($stream, $row);
        }
        fseek($stream, 0);
        $content = stream_get_contents($stream);
        fclose($stream);
        $file = UploadedFile::fake()
            ->createWithContent("file", $content)
            ->mimeType("text/csv");

        $response = $this->post(route("upload"), compact("file"));

        /** @var MessageBag $errors */
        $errors = View::shared("errors");
        $response->assertRedirectContains(route("headers"));
        $response->assertStatus(Response::HTTP_SEE_OTHER);
        $this->assertEmpty($errors->all());
        $this->assertSame($data, Session::get("data"));
    }
}
