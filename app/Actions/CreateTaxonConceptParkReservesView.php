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
CREATE OR REPLACE VIEW mapper.taxon_concept_park_reserves_view
AS SELECT tcp.taxon_concept_id,
    pr.id AS park_reserve_id,
    pr.name AS park_reserve_name,
    pr.name_short AS park_reserve_short_name,
    pr.area_type,
    tcp.occurrence_status,
    tcp.establishment_means,
    tcp.degree_of_establishment,
    pr.geom
FROM mapper.taxon_concept_park_reserves tcp
JOIN mapper_overlays.park_reserves pr ON tcp.area_id = pr.id
SQL;
        DB::connection($this->connection)->statement($sql);
    }
}
