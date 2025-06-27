<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class CreateTaxonConceptIbra7SubregionsView
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
CREATE OR REPLACE VIEW mapper.taxon_concept_ibra7_subregions_view AS 
SELECT tcir.taxon_concept_id,
    ir.id AS ibra7_subregion_id,
    ir.name AS ibra7_subregion_name,
    ir.code AS ibra7_subregion_code,
    tcir.occurrence_status,
    tcir.establishment_means,
    tcir.degree_of_establishment,
    ir.geom
FROM mapper.taxon_concept_ibra7_subregions tcir
JOIN mapper_overlays.ibra7_subregions ir ON tcir.area_id = ir.id
SQL;
        DB::connection($this->connection)->statement($sql);
    }
}
