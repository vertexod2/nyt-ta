<?php

namespace Tests\Feature;

use App\Exceptions\NYTException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NYTControllerTest extends TestCase
{
    public function test_throw_exception_when_request_failed(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(NYTException::class);

        Http::fake(['api.nytimes.com*' => Http::response([], Response::HTTP_REQUEST_TIMEOUT)]);
        $this->call('GET', 'api/1/nyt/best-sellers');
    }

    /**
     * @dataProvider queryCases
     */
    public function test_search_query_cases($params, $expected): void
    {
        Http::fake(['api.nytimes.com*' => Http::response([])]);

        $response = $this->get(route('nyt-best-sellers', $params));
        $response->assertStatus($expected);
    }

    private function queryCases(): array
    {
        return [
            'success_with_all_fields' => [
                [
                    'author' => 'Daniela Gabrinus',
                    'title' => 'In the mid of the night',
                    'isbn' => [1234567890, 1234567890123],
                    'offset' => 0
                ],
                Response::HTTP_OK,
            ],
            'bad_request_wrong_offset_multiplier' => [
                ['offset' => 30],
                Response::HTTP_BAD_REQUEST,
            ],
            'bad_request_author_too_short' => [
                ['author' => 'D'],
                Response::HTTP_BAD_REQUEST,
            ],
            'bad_request_title_too_short' => [
                ['title' => 'A'],
                Response::HTTP_BAD_REQUEST,
            ],
            'bad_request_isbn_is_not_array' => [
                ['isbn' => 1234567890],
                Response::HTTP_BAD_REQUEST,
            ],
            'bad_request_isbn_wrong_size' => [
                ['isbn' => [12345678901]],
                Response::HTTP_BAD_REQUEST,
            ],
        ];
    }
}
