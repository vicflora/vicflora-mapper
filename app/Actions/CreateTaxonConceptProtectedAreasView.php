<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class CreateTaxonConceptProtectedAreasView
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
CREATE OR REPLACE VIEW mapper.taxon_concept_protected_areas_view
AS SELECT tcpa.taxon_concept_id,
    pa.id AS protected_area_id,
    pa.name,
    pa.type,
    pa.type_abbr,
    pa.state,
    pa.geom
FROM mapper.taxon_concept_protected_areas tcpa
JOIN mapper_overlays.protected_areas pa ON tcpa.area_id = pa.id
SQL;
        DB::connection($this->connection)->statement($sql);
    }
}
