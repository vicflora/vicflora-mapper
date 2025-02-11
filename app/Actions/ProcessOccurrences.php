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

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessOccurrences {


    public function __invoke(string $dataSource='AVH', string $schema='ala', $pageSize=1000, $startIndex=0)
    {
        $table = strtolower($dataSource) . '_data';

        if (!Schema::hasColumn("$schema.$table", 'row_index')) {
            Schema::table("$schema.$table", function (Blueprint $table) {
                $table->bigIncrements('row_index');
            });
        }

        $total = DB::table("$schema.$table")->count();

        while ($startIndex < $total) {
            $occurrences = DB::table("$schema.$table as d")
            ->leftJoin('mapper.parsed_names as pn',
                    'd.unprocessed_scientific_name', '=',
                    'pn.scientific_name')
            ->select(
                'd.uuid as id',
                DB::raw('now() as created_at'),
                DB::raw('now() as updated_at'),
                'd.data_resource_uid',
                'd.collection',
                'd.catalog_number',
                'd.unprocessed_scientific_name as scientific_name',
                'd.recorded_by',
                'd.record_number',
                'd.event_date',
                'd.locality',
                'd.verbatim_locality',
                'd.latitude as decimal_latitude',
                'd.longitude as decimal_longitude',
                DB::raw("public.ST_PointFromText('POINT('||d.longitude||' '||d.latitude||')', 4326) as geom"),
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
            ->where('d.row_index', '>', $startIndex)
            ->limit($pageSize);

            $data = $occurrences->get()->map(fn ($row) => (array) $row);
            DB::table('mapper.occurrences')->insertOrIgnore($data->toArray());

            $startIndex += $pageSize;
        }

        Schema::table("$schema.$table", function(Blueprint $table) {
            $table->dropColumn('row_index');
        });

        return 0;
    }
}
