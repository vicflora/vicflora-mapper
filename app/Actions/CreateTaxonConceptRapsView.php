<?php

namespace App\Actions;

class CreateTaxonConceptRapsView
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
CREATE OR REPLACE VIEW mapper.taxon_concept_raps_view AS 
SELECT tcl.taxon_concept_id,
    a.id AS area_id,
    a.name AS area_name,
    a.short_name AS area_short_name,
    a.traditional_owners,
    tcl.occurrence_status,
    tcl.establishment_means,
    tcl.degree_of_establishment,
    a.geom
FROM mapper.taxon_concept_raps tcl
JOIN mapper.raps a ON tcl.area_id = a.id
SQL;
    }
}
