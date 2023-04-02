<?php

namespace App\Http\Controllers;

use App\Exceptions\NYTException;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Facades\Http;

class NYTController extends Controller
{
    const NYT_API_BESTSELLER_URL = 'https://api.nytimes.com/svc/books/v3/lists/best-sellers/history.json';

    protected array $searchOnlyBy = ['author', 'isbn', 'title', 'offset'];

    public function index(SearchRequest $request)
    {
        $query = array_merge([
            'api-key' => env('NYT_API_KEY')
        ], $request->only($this->searchOnlyBy));

        $response = Http::get(self::NYT_API_BESTSELLER_URL, $query);

        if ($response->failed()) {
            throw new NYTException("Request cannot be processed", $response->status());
        }

        return $response->json();
    }
}
