<?php

namespace App\Actions\VicFlora;

use Illuminate\Support\Facades\DB;

class CreateTaxonConceptCatchmentsView
{
    protected string $connection;

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
CREATE OR REPLACE VIEW mapper.taxon_concept_catchments_view AS 
SELECT tcc.taxon_concept_id,
    c.id AS area_id,
    c.area_name,
    c.area_code,
    tcc.occurrence_status,
    tcc.establishment_means,
    tcc.degree_of_establishment,
    c.geom
FROM mapper.taxon_concept_catchments tcc
JOIN mapper_overlays.catchments c ON tcc.area_id = c.id;
SQL;
        DB::connection($this->connection)->statement($sql);
    }
}