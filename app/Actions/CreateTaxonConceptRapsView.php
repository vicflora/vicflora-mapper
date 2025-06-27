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
CREATE OR REPLACE VIEW mapper.taxon_concept_raps_view
AS SELECT tcr.taxon_concept_id,
    r.id AS rap_id,
    r.name AS rap_name,
    r.short_name AS rap_short_name,
    r.traditional_owners,
    tcr.occurrence_status,
    tcr.establishment_means,
    tcr.degree_of_establishment,
    r.geom
FROM mapper.taxon_concept_raps tcr
JOIN mapper_overlays.raps r ON tcr.area_id = r.id
SQL;
    }
}
