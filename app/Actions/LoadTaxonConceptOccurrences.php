<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class LoadTaxonConceptOccurrences
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
        $taxonConceptOccurrences = DB::connection($this->connection)->table('mapper.taxa as t')
                ->join('mapper.parsed_names as pn', 't.scientific_name_id', '=', 'pn.vicflora_scientific_name_id')
                ->join('mapper.occurrences as o', 'pn.id', '=', 'o.parsed_name_id')
                ->select('t.accepted_name_usage_id as taxon_concept_id', 'o.id as occurrence_id')
                ->union(
                    DB::connection($this->connection)->table('mapper.taxa as t')
                        ->join('mapper.parsed_names as pn', 't.scientific_name_id', '=', 'pn.vicflora_scientific_name_id')
                        ->join('mapper.occurrences as o', 'pn.id', '=', 'o.parsed_name_id')
                        ->select('t.species_id as taxon_concept_id', 'o.id as occurrence_id')
                );

        DB::connection($this->connection)->table('mapper.taxon_concept_occurrences')->insertUsing([
            'taxon_concept_id', 'occurrence_id'
        ], $taxonConceptOccurrences);
    }
}
