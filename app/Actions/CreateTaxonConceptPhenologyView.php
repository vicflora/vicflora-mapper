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

class CreateTaxonConceptPhenologyView {
    
    public function __invoke()
    {
        $sql = <<<SQL
create view mapper.taxon_concept_phenology_view as
select 
	t.id as taxon_concept_id, 
	t.scientific_name, 
	substring(o.event_date from 6 for 2)::integer as month_numerical,
	CASE substring(o.event_date::text, 6, 2)::integer
		WHEN 1 THEN 'January'::text
		WHEN 2 THEN 'February'::text
		WHEN 3 THEN 'March'::text
		WHEN 4 THEN 'April'::text
		WHEN 5 THEN 'May'::text
		WHEN 6 THEN 'June'::text
		WHEN 7 THEN 'July'::text
		WHEN 8 THEN 'August'::text
		WHEN 9 THEN 'September'::text
		WHEN 10 THEN 'October'::text
		WHEN 11 THEN 'November'::text
		WHEN 12 THEN 'December'::text
		ELSE NULL::text
	END AS month,
	count(*) as total, 
	count(o.buds) as buds,
	count(o.flowers) as flowers,
	count(o.fruit) as fruit
from mapper.taxon_concept_occurrences tco
join mapper.taxa t on tco.taxon_concept_id = t.id
join mapper.occurrences o on tco.occurrence_id = o.id
where o.event_date ~ '\d{4}-\d{2}(\d{2})?'
group by t.id, substring(o.event_date from 6 for 2)::integer
order by scientific_name, month_numerical
SQL;
        DB::statement($sql);

        return 0;
    }
}