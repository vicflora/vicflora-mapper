<?php

namespace App\Actions\VicFlora;

use Illuminate\Support\Facades\DB;

class LoadOccurrences
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
    public function __invoke(string $table, int $pageSize): void
    {
        $count = $pageSize;
        $start = 1;
        $end = $pageSize;
        while ($count == $pageSize) {
            $occurrences = DB::connection('vicflora')
                ->query()
                ->select(
                    'd.uuid as id',
                    DB::raw('now() as created_at'),
                    DB::raw('now() as updated_at'),
                    'd.basis_of_record',
                    'd.data_resource_uid',
                    'd.collection',
                    'd.catalog_number',
                    'd.unprocessed_scientific_name as scientific_name',
                    'd.recorded_by',
                    'd.record_number',
                    'd.event_date',
                    'd.country',
                    'd.state_province',
                    'd.locality',
                    'd.verbatim_locality',
                    'd.latitude as decimal_latitude',
                    'd.longitude as decimal_longitude',
                    DB::raw("public.ST_PointFromText('POINT('||d.longitude||' '||d.latitude||')', 4326) as geom"),
                    'd.ibra7_region',
                    'd.ibra7_subregion',
                    'd.lga2023',
                    'd.capad2022',
                    'pn.id as parsed_name_id',
                    DB::raw(
                        "case
                            when d.establishment_means = 'native' or d.degree_of_establishment = 'native' then 'native'
                            when d.establishment_means = 'unknown' then 'uncertain'
                            when d.establishment_means = 'cultivated' or d.degree_of_establishment = 'cultivated' then 'introduced'
                            when d.establishment_means = 'naturalised' then 'introduced'
                            when d.establishment_means = '' then null
                            else substring(d.establishment_means from 1 for 32)
                        end as establishment_means"
                    ),
                    DB::raw(
                        "case
                            when d.establishment_means = 'native' or d.degree_of_establishment = 'native' then 'native'
                            when d.establishment_means = 'cultivated' or d.degree_of_establishment = 'cultivated' then 'cultivated'
                            when d.establishment_means = 'naturalised' then 'established'
                            when d.establishment_means = '' then null
                            else substring(d.degree_of_establishment from 1 for 32)
                        end as degree_of_establishment"
                    ),
                    DB::raw("case when d.reproductive_condition like '%flowers%' then true else null end as flowers"),
                    DB::raw("case when d.reproductive_condition like '%fruit%' then true else null end as fruit"),
                    DB::raw("case when d.reproductive_condition like '%buds%' then true else null end as buds"),
                    DB::raw("case d.data_resource_uid when 'dr1097' then 'VBA' else 'AVH' end as data_source")
                )
                ->from("ala.$table as d")
                ->leftJoin('mapper.parsed_names as pn',
                            'd.unprocessed_scientific_name', '=',
                            'pn.scientific_name')
                ->whereBetween('row_index', [$start, $end])
                ->get();

            $count = $occurrences->count();
            $start += $pageSize;
            $end += $pageSize;

            $insertData = $occurrences->filter(fn ($occurrence) => $occurrence->scientific_name)
                ->map(fn ($occurrence) => (array) $occurrence)->toArray();
            DB::connection('vicflora')->table('mapper.occurrences')->insert($insertData);
        }
    }
}
