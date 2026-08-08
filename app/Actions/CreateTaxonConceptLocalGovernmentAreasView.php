<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class CreateTaxonConceptLocalGovernmentAreasView
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
CREATE OR REPLACE VIEW mapper.taxon_concept_lgas_view
AS SELECT tcl.taxon_concept_id,
    a.id AS lga_id,
    a.name AS lga_name,
    a.code AS lga_code,
    tcl.occurrence_status,
    tcl.establishment_means,
    tcl.degree_of_establishment,
    a.geom
FROM mapper.taxon_concept_lga_mv tcl
JOIN mapper.local_government_areas a ON tcl.area_id = a.id AND a.state::text = 'Victoria'::text
SQL;
        DB::connection($this->connection)->statement($sql);
    }
}
