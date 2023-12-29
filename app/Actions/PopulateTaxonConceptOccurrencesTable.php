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

class PopulateTaxonConceptOccurrencesTable {
    
    public function __invoke()
    {
        $first = DB::table('taxa as t')
                ->join('parsed_names as pn', 't.scientific_name_id', '=', 'pn.vicflora_scientific_name_id')
                ->join('occurrences as o', 'pn.id', '=', 'o.parsed_name_id')
                ->select('t.species_id as taxon_concept_id', 'o.id as occurrence_id');

        $select = DB::table('taxa as t')
                ->join('parsed_names as pn', 't.scientific_name_id', '=', 'pn.vicflora_scientific_name_id')
                ->join('occurrences as o', 'pn.id', '=', 'o.parsed_name_id')
                ->select('t.accepted_name_usage_id as taxon_concept_id', 'o.id as occurrence_id')
                ->union($first);

        DB::table('taxon_concept_occurrences')->insertUsing([
            'taxon_concept_id', 'occurrence_id'
        ], $select);
        
        return 0;
    }
}