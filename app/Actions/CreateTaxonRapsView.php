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

class CreateTaxonRapsView {
    
    public function __invoke()
    {
        $sql = <<<SQL
create view mapper.taxon_raps_view as
select 
	tcr.taxon_concept_id,
	r.id as rap_id,
	r.name as rap_name,
	r.short_name as rap_short_name,
    r.traditional_owners,
	tcr.occurrence_status,
	tcr.establishment_means,
	tcr.degree_of_establishment,
	r.geom
from mapper.taxon_concept_raps tcr
join mapper_overlays.raps r on tcr.rap_id = r.id
SQL;
        DB::statement($sql);

        return 0;
    }
}