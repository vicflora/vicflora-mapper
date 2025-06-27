<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class CreateTaxonConceptOccurrencesMaterializedView
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
CREATE MATERIALIZED VIEW IF NOT EXISTS mapper.taxon_occurrences_materialized_view AS
SELECT
    tco.taxon_concept_id,
    t.scientific_name,
    o.id AS occurrence_id,
    o.catalog_number,
    o.recorded_by,
    o.record_number,
    o.event_date,
    o.decimal_longitude,
    o.decimal_latitude,
    o.geom,
    CASE o.data_resource_uid
        WHEN 'dr1097'::text THEN 'VBA'::text
        ELSE 'AVH'::text
    END AS data_source,
    o.collection,
    COALESCE(aocc.asserted_value, t.occurrence_status,
            'present'::character varying) AS occurrence_status,
    CASE
        WHEN aocc.asserted_value IS NOT NULL THEN 'assertion'::text
        WHEN t.occurrence_status IS NOT NULL AND
                t.occurrence_status::text <> ''::text THEN 'taxon'::text
        ELSE NULL::text
    END AS occurrence_status_source,
    COALESCE(aest.asserted_value, o.establishment_means, t.establishment_means,
            'native'::character varying) AS establishment_means,
    CASE
        WHEN aest.asserted_value IS NOT NULL THEN 'assertion'::text
        WHEN o.establishment_means IS NOT NULL THEN
        CASE o.data_resource_uid
            WHEN 'dr1097'::text THEN 'VBA'::text
            ELSE 'AVH'::text
        END
        WHEN t.establishment_means IS NOT NULL AND
                t.establishment_means::text <> ''::text THEN 'taxon'::text
        ELSE NULL::text
    END AS establishment_means_source,
    COALESCE(adeg.asserted_value, o.degree_of_establishment,
            t.degree_of_establishment, 'native'::character varying)
            AS degree_of_establishment,
    CASE
        WHEN adeg.asserted_value IS NOT NULL THEN 'assertion'::text
        WHEN o.degree_of_establishment IS NOT NULL THEN
        CASE o.data_resource_uid
            WHEN 'dr1097'::text THEN 'VBA'::text
            ELSE 'AVH'::text
        END
        WHEN t.degree_of_establishment IS NOT NULL AND
                t.degree_of_establishment::text <> ''::text THEN 'taxon'::text
        ELSE NULL::text
    END AS degree_of_establishment_source,
    o.scientific_name AS provided_scientific_name
FROM mapper.taxon_concept_occurrences tco
JOIN mapper.occurrences o ON tco.occurrence_id = o.id
LEFT JOIN public.assertions aocc ON o.id = aocc.occurrence_id
        AND aocc.term::text = 'occurrenceStatus'::text
LEFT JOIN public.assertions aest ON o.id = aest.occurrence_id
        AND aest.term::text = 'establishmentMeans'::text
LEFT JOIN public.assertions adeg ON o.id = adeg.occurrence_id
        AND adeg.term::text = 'degreeOfEstablishment'::text
JOIN mapper.taxa t ON tco.taxon_concept_id = t.id
SQL;
        DB::connection($this->connection)->statement($sql);


        DB::connection($this->connection)->statement('create index
            taxon_occurrences_materialized_view_taxon_concept_id_index
            on mapper.taxon_occurrences_materialized_view
            (taxon_concept_id)');

        DB::connection($this->connection)->statement('create index
            taxon_occurrences_materialized_view_geom_spatial_index
            on mapper.taxon_occurrences_materialized_view using gist
            (geom)');
    }
}
