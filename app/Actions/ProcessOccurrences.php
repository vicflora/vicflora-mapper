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

use Illuminate\Support\Facades\DB;

class ProcessOccurrences {

    private $chunk;
    private $start;
    
    public function __invoke(string $table, string $schema='ala')
    {
        /*
select 
	d.uuid,
	now() as created_at,
	now() as updated_at,
	d.data_resource_uid,
	d.collection,
	d.catalog_number,
	d.unprocessed_scientific_name,
	d.recorded_by,
	d.record_number,
	d.event_date,
	d.locality,
	d.verbatim_locality,
	d.latitude,
	d.longitude,
	ST_PointFromText('POINT('||d.longitude||' '||d.latitude||')', 4326),
	pn.id as parsed_name_id,
	
	case
		when d.establishment_means = 'native' or d.degree_of_establishment = 'native' then 'native'
		when d.establishment_means = 'unknown' then 'uncertain'
		when d.establishment_means = 'cultivated' or d.degree_of_establishment = 'cultivated' then 'introduced'
		when d.establishment_means = 'naturalised' then 'introduced'
		else d.establishment_means 
	end as establishment_means,
	case
		when d.establishment_means = 'native' or d.degree_of_establishment = 'native' then 'native'
		when d.establishment_means = 'cultivated' or d.degree_of_establishment = 'cultivated' then 'cultivated'
		when d.establishment_means = 'naturalised' then 'established'
		else d.degree_of_establishment 
	end as establishment_means,
	case when d.reproductive_condition like '%flowers%' then true else null end as flowers,
	case when d.reproductive_condition like '%fruit%' then true else null end as fruit,
	case when d.reproductive_condition like '%buds%' then true else null end as buds
from ala.avh_data d 
left join mapper.parsed_names pn on d.unprocessed_scientific_name = pn.scientific_name
limit 1000;        */
        $this->start = 0;
        $this->chunk = 1000;

        DB::table("$schema.$table as d")
                ->leftJoin('mapper.parsed_names as pn', 
                        'd.unprocessed_scientific_name', '=', 
                        'pn.scientific_name')
                ->select(
                    'd.uuid',
                    DB::raw('now() as created_at'),
                    DB::raw('now() as updated_at'),
                    'd.data_resource_uid',
                    'd.collection',
                    'd.catalog_number',
                    'd.unprocessed_scientific_name',
                    'd.recorded_by',
                    'd.record_number',
                    'd.event_date',
                    'd.locality',
                    'd.verbatim_locality',
                    'd.latitude',
                    'd.longitude',
                    DB::raw("public.ST_PointFromText('POINT('||d.longitude||' '||d.latitude||')', 4326) as geom"),
                    'pn.id as parsed_name_id',
                    DB::raw(
                        "case
                            when d.establishment_means = 'native' or d.degree_of_establishment = 'native' then 'native'
                            when d.establishment_means = 'unknown' then 'uncertain'
                            when d.establishment_means = 'cultivated' or d.degree_of_establishment = 'cultivated' then 'introduced'
                            when d.establishment_means = 'naturalised' then 'introduced'
                            else d.establishment_means 
                        end as establishment_means"
                    ),
                    DB::raw(
                        "case
                            when d.establishment_means = 'native' or d.degree_of_establishment = 'native' then 'native'
                            when d.establishment_means = 'cultivated' or d.degree_of_establishment = 'cultivated' then 'cultivated'
                            when d.establishment_means = 'naturalised' then 'established'
                            else d.degree_of_establishment 
                        end as degree_of_establishment"
                    ),
                    DB::raw("case when d.reproductive_condition like '%flowers%' then true else null end as flowers"),
                    DB::raw("case when d.reproductive_condition like '%fruit%' then true else null end as fruit"),
                    DB::raw("case when d.reproductive_condition like '%buds%' then true else null end as buds")
                )
                ->orderBy('d.uuid')
                ->chunk($this->chunk, function($rows) {
                    // echo "$this->start\n";
                    $data = [];
                    foreach ($rows as $row) {
                        $insert = [
                            'id' => $row->uuid,
                            'created_at' => $row->created_at,
                            'updated_at' => $row->updated_at,
                            'data_resource_uid' => $row->data_resource_uid,
                            'collection' => $row->collection,
                            'catalog_number' => $row->catalog_number,
                            'scientific_name' => $row->unprocessed_scientific_name,
                            'recorded_by' => $row->recorded_by,
                            'record_number' => $row->record_number,
                            'event_date' => $row->event_date,
                            'locality' => $row->locality,
                            'verbatim_locality' => $row->verbatim_locality,
                            'decimal_latitude' => $row->latitude,
                            'decimal_longitude' => $row->longitude,
                            'geom' => $row->geom,
                            'parsed_name_id' => $row->parsed_name_id,
                            'establishment_means' => $row->establishment_means,
                            'degree_of_establishment' => $row->degree_of_establishment,
                            'flowers' => $row->flowers,
                            'fruit' => $row->fruit,
                            'buds' => $row->buds,
                        ];

                        $data[] = $insert;
                    }

                    DB::table('occurrences')->insert($data);

                    $this->start += $this->chunk;
                });


        return 0;
    }
}