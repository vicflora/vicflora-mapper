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

class CreateTaxonBioregionsView {
    
    public function __invoke()
    {
        $sql = <<<SQL
create view mapper.taxon_bioregions_view as
select 
	tcb.taxon_concept_id,
	b.id as bioregion_id,
	b.bioregion as bioregion_name,
	b.bioregcode as bioregion_code,
	tcb.occurrence_status,
	tcb.establishment_means,
	tcb.degree_of_establishment,
	b.geom
from mapper.taxon_concept_bioregions tcb
join mapper_overlays.bioregions b on tcb.bioregion_id = b.id
SQL;
        DB::statement($sql);

        return 0;
    }
}