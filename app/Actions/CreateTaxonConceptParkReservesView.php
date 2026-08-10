<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class CreateTaxonConceptParkReservesView
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
CREATE OR REPLACE VIEW mapper.taxon_concept_park_reserves_view AS 
SELECT tcl.taxon_concept_id,
    a.id AS area_id,
    a.name AS area_name,
    a.name_short,
    a.area_type,
    tcl.occurrence_status,
    tcl.establishment_means,
    tcl.degree_of_establishment,
    a.geom
FROM mapper.taxon_concept_park_reserves tcl
JOIN mapper.park_reserves a ON tcl.area_id = a.id
SQL;
        DB::connection($this->connection)->statement($sql);
    }
}
