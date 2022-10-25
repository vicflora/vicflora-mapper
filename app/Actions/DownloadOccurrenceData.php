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

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Query;

class DownloadOccurrenceData {

    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }
    
    public function __invoke(string $q, string $table)
    {
        $fields = [
            'id',
            'data_resource_uid',
            'collectionName',
            'catalogue_number',
            'raw_taxon_name',
            'latitude',
            'longitude',
            'recordedBy',
            'recordNumber',
            'raw_eventDate',
            'raw_establishmentMeans',
            'raw_degreeOfEstablishment',
            'raw_locality',
            'verbatimLocality',
            'reproductiveCondition',
        ];

        $fq = [
            'state:Victoria',
            'latitude:[* TO *]',
            'longitude:[* TO *]',
            '-raw_identification_qualifier:[* TO *]',
            'kingdom:Plantae',
        ];

        $query = [
            'q' => $q,
            'fq' => $fq,
            'fields' => implode(',', $fields),
            'qa' => 'none',
            'email' => 'Niels.Klazenga@rbg.vic.gov.au',
            'emailNotify' => false,
            'reasonType' => 4,
        ];

        $queryString = Query::build($query);

        $url = 'https://biocache-ws.ala.org.au/ws/occurrences/offline/download';

        $res = $this->client->request('GET', $url . '?' . $queryString);

        $result = json_decode($res->getBody());

        $getDownload = new GetAlaDownload;
        $getDownload($result->statusUrl, $table);

        return 0;
    }
}