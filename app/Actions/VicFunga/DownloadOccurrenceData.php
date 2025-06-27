<?php

namespace App\Actions\VicFunga;

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
    public function __invoke(): void
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
            'kingdom:(Fungi OR Protozoa OR Chromista)',
            '-occurrenceStatus:ABSENT',
            'country:Australia',
            'decimalLatitude:*',
            'decimalLongitude:*',
            '-raw_identificationQualifier:*',
            '-userAssertions:50001',
            '-userAssertions:50005',
            'raw_scientificName:*'
        ];

        $query = [
            'q' => '*:*',
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

        $result = json_decode($res->getBody());

        (new GetAlaDownload)($result->statusUrl, 'fungi_data');
    }
}
