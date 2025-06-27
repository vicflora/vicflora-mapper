<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class QueryGbifNameParser
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    
    public function parse(array $names)
    {
        $response = Http::withBody(json_encode($names), 'application/json')
                ->post('https://api.gbif.org/v1/parser/name');

        return $response->json();
    }
}
