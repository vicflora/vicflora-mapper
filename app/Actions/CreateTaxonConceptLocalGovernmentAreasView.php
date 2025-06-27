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
CREATE OR REPLACE VIEW mapper.taxon_concept_local_government_areas_view
AS SELECT tclga.taxon_concept_id,
    lga.id AS local_government_area_id,
    lga.name,
    lga.state,
    lga.geom
FROM mapper.taxon_concept_local_government_areas tclga
JOIN mapper_overlays.local_government_areas lga ON tclga.area_id = lga.id
SQL;
        DB::connection($this->connection)->statement($sql);
    }
}
