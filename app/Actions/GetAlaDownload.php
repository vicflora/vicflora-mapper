<?php
// Copyright 2022 Royal Botanic Gardens Board
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

use App\Actions\ExtractAllFilesFromZipArchive;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GetAlaDownload {

    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function __invoke(string $statusUrl, string $filename, int $wait=30)
    {
        $this->checkStatus($statusUrl, $filename, $wait);
        return 0;
    }

    private function checkStatus($statusUrl, $filename, $wait)
    {
        $res = $this->client->request('GET', $statusUrl);
        $body = $res->getBody();

        $json = json_decode($body);
        if (isset($json->downloadUrl)) {
            $contents = file_get_contents($json->downloadUrl);
            Storage::put("ala/$filename.zip", $contents);
            $extract = new ExtractAllFilesFromZipArchive;
            $archive = storage_path('app/ala/' . $filename) . '.zip';
            $extractTo = storage_path('app/ala/' . $filename);
            $extract($archive, $extractTo);
        }
        else {
            sleep($wait);
            $this->checkStatus($statusUrl, $filename, $wait);
        }

    }
}
