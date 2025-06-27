<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class CreateTaxonConceptBioregionsView
{
    private string $connection;

    /**
     * Create a new class instance.
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * Invoke the class instance.
     */
    public function __invoke(): void
    {
        $sql = <<<SQL
CREATE OR REPLACE VIEW mapper.taxon_concept_bioregions_view
AS SELECT tcb.taxon_concept_id,
    b.id AS bioregion_id,
    b.bioregion AS bioregion_name,
    b.bioregcode AS bioregion_code,
    tcb.occurrence_status,
    tcb.establishment_means,
    tcb.degree_of_establishment,
    b.geom
FROM mapper.taxon_concept_bioregions tcb
JOIN mapper_overlays.bioregions b ON tcb.area_id = b.id;
SQL;
        DB::connection($this->connection)->statement($sql);
    }
}
