<?php

namespace App\Actions;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToTaxonConceptOccurrencesTable
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
        Schema::connection($this->connection)->table('mapper.taxon_concept_occurrences', function(Blueprint $table) {
            $table->index('taxon_concept_id');
            $table->unique(['taxon_concept_id', 'occurrence_id']);
        });
        
    }
}
