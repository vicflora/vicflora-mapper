<?php
// Copyright 2024 Royal Botanic Gardens Board
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

namespace App\Actions;

use GuzzleHttp\Client;

class ParseName
{
    protected $baseUrl = 'https://api.gbif.org/v1/parser/name';

    public function __invoke(string $name)
    {
        $client = new Client();
        $response = $client->request('GET', $this->baseUrl, [
            'query' => ['name' => $name]
        ]);
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            return json_decode($body)[0];
        }
        else {
            return $response->getStatusCode();
        }
    }
}
