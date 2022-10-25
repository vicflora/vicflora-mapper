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

class PopulateTaxonConceptLocalGovernmentAreasTable {
    
    public function __invoke()
    {
        $first = DB::table('taxa')
                ->select('accepted_name_usage_id as taxon_concept_id');

        DB::table('taxa')->select('species_id as taxon_concept_id')
                ->union($first)
                ->orderBy('taxon_concept_id')
                ->chunk(100, function($rows) {
                    $taxonConceptIds = [];
                    foreach ($rows as $row) {
                        $taxonConceptIds[] = $row->taxon_concept_id;
                    }

                    $select = DB::table('taxon_concept_occurrences_view as tco')
                            ->join('mapper_overlays.local_government_areas as lga', function($query) {
                                $query->where(DB::raw("public.ST_Intersects(tco.geom, lga.geom)"), true);
                            })
                            ->whereIn('tco.taxon_concept_id', $taxonConceptIds)
                            ->groupBy('tco.taxon_concept_id', 't.id', 'lga.id')
                            ->select(
                                'tco.taxon_concept_id',
                                'lga.id as local_government_area_id',
                                DB::raw(
                                    "case
                                        when 'present' = ANY (array_agg(tco.occurrence_status)::text[]) then 'present'
                                        when 'endemic' = ANY (array_agg(tco.occurrence_status)::text[]) then 'present'
                                        when 'extinct' = ANY (array_agg(tco.occurrence_status)::text[]) then 'extinct'
                                        when 'doubtful' = ANY (array_agg(tco.occurrence_status)::text[]) then 'doubtful'
                                        ELSE 'present'
                                    end AS occurrence_status"
                                ),
                                DB::raw(
                                    "case
                                        when 'native' = ANY (array_agg(tco.establishment_means)::text[]) then 'native'
                                        when 'naturalised' = ANY (array_agg(tco.establishment_means)::text[]) then 'naturalised'
                                        when 'introduced' = ANY (array_agg(tco.establishment_means)::text[]) then 'introduced'
                                        when 'cultivated' = ANY (array_agg(tco.establishment_means)::text[]) then 'cultivated'
                                        when 'uncertain' = ANY (array_agg(tco.establishment_means)::text[]) then 'uncertain'
                                        ELSE 'native'
                                    end AS establishment_means"
                                ),
                                DB::raw(
                                    "case
                                        when 'native' = ANY (array_agg(tco.degree_of_establishment)::text[]) then 'native'
                                        when 'invasive' = ANY (array_agg(tco.degree_of_establishment)::text[]) then 'invasive'
                                        when 'established' = ANY (array_agg(tco.degree_of_establishment)::text[]) then 'established'
                                        when 'reproducing' = ANY (array_agg(tco.degree_of_establishment)::text[]) then 'reproducing'
                                        when 'casual' = ANY (array_agg(tco.degree_of_establishment)::text[]) then 'casual'
                                        when 'cultivated' = ANY (array_agg(tco.degree_of_establishment)::text[]) then 'cultivated'
                                        ELSE 'native'
                                    end AS degree_of_establishment"
                                )
                            );

                    DB::table('taxon_concept_local_government_areas')->insertUsing([
                        'taxon_concept_id',
                        'local_government_area_id',
                        'occurrence_status',
                        'establishment_means',
                        'degree_of_establishment',
                    ], $select);
                });
        return 0;
    }
}