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

class CreateTaxonParkReservesView {
    
    public function __invoke()
    {
        $sql = <<<SQL
create view mapper.taxon_park_reserves_view as
select 
	tcp.taxon_concept_id,
	pr.id as park_reserve_id,
	pr.name as park_reserve_name,
	pr.name_short as park_reserve_short_name,
    pr.area_type,
	tcp.occurrence_status,
	tcp.establishment_means,
	tcp.degree_of_establishment,
	pr.geom
from mapper.taxon_concept_park_reserves tcp
join mapper_overlays.park_reserves pr on tcp.park_reserve_id = pr.id
SQL;
        DB::statement($sql);

        return 0;
    }
}