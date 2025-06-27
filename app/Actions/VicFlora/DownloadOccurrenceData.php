<?php

namespace App\Actions\VicFlora;

use App\Actions\GetAlaDownload;
use GuzzleHttp\Psr7\Query;
use Illuminate\Support\Facades\Http;

class DownloadOccurrenceData
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Invoke the class instance.
     */
    public function __invoke(string $q, string $table): void
    {
        $fields = [
            'id',
            'dataResourceUid',
            'basisOfRecord',
            'collectionName',
            'catalogue_number',
            'raw_scientificName',
            'latitude',
            'longitude',
            'recordedBy',
            'recordNumber',
            'eventDate',
            'raw_establishmentMeans',
            'raw_degreeOfEstablishment',
            'country',
            'stateProvince',
            'raw_locality',
            'verbatimLocality',
            'reproductiveCondition',
            'cl11219',
            'cl11170',
            'cl1048',
            'cl1049',
        ];

        $fq = [
            'stateProvince:Victoria',
            'decimalLatitude:*',
            'decimalLongitude:*',
            '-raw_identificationQualifier:*',
            'kingdom:Plantae',
            '-userAssertions:50001',
            '-userAssertions:50005'
        ];

        $query = [
            'q' => $q,
            'fq' => $fq,
            'fields' => implode(',', $fields),
            'qa' => 'none',
            'email' => 'Niels.Klazenga@rbg.vic.gov.au',
            'emailNotify' => false,
            'reasonType' => 4,
            'disableAllQualityFilters' => 'true',
        ];

        $queryString = Query::build($query);

        $url = 'https://biocache-ws.ala.org.au/ws/occurrences/offline/download';

        $res = Http::get($url . '?' . $queryString);

        $result = $res->json();

        (new GetAlaDownload)(statusUrl: $result['statusUrl'], filename: $table);
    }
}
