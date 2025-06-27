<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class CreateTaxonConceptPhenologyView
{
    private string $connection;
    /**
     * Create a new class instance.
     */
    public function __construct(string $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Invoke the class instance.
     */
    public function __invoke(): void
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
        DB::connection($this->connection)->statement($sql);
    }
}
