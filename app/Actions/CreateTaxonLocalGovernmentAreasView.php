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

class CreateTaxonLocalGovernmentAreasView {
    
    public function __invoke()
    {
        $sql = <<<SQL
create view mapper.taxon_local_government_areas_view as
select 
	tcl.taxon_concept_id,
	lga.id as local_government_area_id,
	lga.lga_name as local_government_area_name,
	lga.abb_name as local_government_area_abbr_name,
	tcl.occurrence_status,
	tcl.establishment_means,
	tcl.degree_of_establishment,
	lga.geom
from mapper.taxon_concept_local_government_areas tcl
join mapper_overlays.local_government_areas lga on tcl.local_government_area_id = lga.id
SQL;
        DB::statement($sql);

        return 0;
    }
}