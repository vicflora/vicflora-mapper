<?php

namespace App\Actions;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonConceptAreasTable
{
    private string $connection;

    /**
     * Create a new class instance.
     *
     * @param mixed $connection 
     * @return void
     */
    public function __construct(string $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Invoke the class instance.
     *
     * @param mixed $layer
     * @return void
     */
    public function __invoke(string $layer): void
    {
        Schema::connection($this->connection)->create('mapper.taxon_concept_' . $layer, function (Blueprint $table) {
            $table->timestampsTz();
            $table->uuid('taxon_concept_id');
            $table->integer('area_id');
            $table->string('occurrence_status', 32);
            $table->string('establishment_means', 32);
            $table->string('degree_of_establishment', 32);
        });
    }
}
