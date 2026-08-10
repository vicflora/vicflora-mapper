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
AS SELECT tclga.taxon_concept_id,
    a.id AS lga_id,
    a.lga_name,
    a.lga_pid AS lga_code,
    tclga.occurrence_status,
    tclga.establishment_means,
    tclga.degree_of_establishment,
    a.geom
FROM mapper.taxon_concept_local_government_areas tclga
JOIN mapper_overlays.local_government_areas a ON tclga.area_id = a.id
SQL;
        DB::connection($this->connection)->statement($sql);
    }
}
