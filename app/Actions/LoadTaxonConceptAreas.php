<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class LoadTaxonConceptAreas
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
    public function __invoke(string $layer, int $pageSize=1000): void
    {
        DB::connection($this->connection)->table('mapper.taxa')->select('species_id as taxon_concept_id')
            ->union(
                DB::connection($this->connection)->table('mapper.taxa')
                    ->select('accepted_name_usage_id as taxon_concept_id')
            )
            ->orderBy('taxon_concept_id')
            ->chunk($pageSize, function($rows) use ($layer) {
                $taxonConceptIds = [];
                foreach ($rows as $row) {
                    $taxonConceptIds[] = $row->taxon_concept_id;
                }

                $select = DB::connection($this->connection)->table('mapper.taxon_occurrences_materialized_view as tco')
                    ->join("mapper_overlays.$layer as l", function($query) {
                        $query->where(DB::raw("public.ST_Intersects(tco.geom, l.geom)"), true);
                    })
                    ->whereIn('tco.taxon_concept_id', $taxonConceptIds)
                    ->where('tco.occurrence_status', 'present')
                    ->groupBy('tco.taxon_concept_id', 'l.id')
                    ->select(
                        'tco.taxon_concept_id',
                        'l.id as area_id',
                        DB::raw(
                            "case
                                when 'present' = ANY (array_agg(tco.occurrence_status)::text[]) then 'present'
                                when 'endemic' = ANY (array_agg(tco.occurrence_status)::text[]) then 'present'
                                when 'extinct' = ANY (array_agg(tco.occurrence_status)::text[]) then 'extinct'
                                when 'doubtful' = ANY (array_agg(tco.occurrence_status)::text[]) then 'doubtful'
                                ELSE 'present'
                            end AS occurrence_status"
                        ),
                        DB::raw(
                            "case
                                when 'native' = ANY (array_agg(tco.establishment_means)::text[]) then 'native'
                                when 'naturalised' = ANY (array_agg(tco.establishment_means)::text[]) then 'naturalised'
                                when 'introduced' = ANY (array_agg(tco.establishment_means)::text[]) then 'introduced'
                                when 'cultivated' = ANY (array_agg(tco.establishment_means)::text[]) then 'cultivated'
                                when 'uncertain' = ANY (array_agg(tco.establishment_means)::text[]) then 'uncertain'
                                ELSE 'native'
                            end AS establishment_means"
                        ),
                        DB::raw(
                            "case
                                when 'native' = ANY (array_agg(tco.degree_of_establishment)::text[]) then 'native'
                                when 'invasive' = ANY (array_agg(tco.degree_of_establishment)::text[]) then 'invasive'
                                when 'established' = ANY (array_agg(tco.degree_of_establishment)::text[]) then 'established'
                                when 'reproducing' = ANY (array_agg(tco.degree_of_establishment)::text[]) then 'reproducing'
                                when 'casual' = ANY (array_agg(tco.degree_of_establishment)::text[]) then 'casual'
                                when 'cultivated' = ANY (array_agg(tco.degree_of_establishment)::text[]) then 'cultivated'
                                ELSE 'native'
                            end AS degree_of_establishment"
                        )
                    );

                    DB::connection($this->connection)->table("mapper.taxon_concept_$layer")->insertUsing([
                        'taxon_concept_id',
                        'area_id',
                        'occurrence_status',
                        'establishment_means',
                        'degree_of_establishment',
                    ], $select);
                });
    }
}
