<?php

namespace App\Actions;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonOccurrencesTable
{        
    /**
     * Database connection to use
     *
     * @var string
     */
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
        Schema::connection($this->connection)->create('mapper.taxon_concept_occurrences', function (Blueprint $table) {
            $table->uuid('taxon_concept_id');
            $table->uuid('occurrence_id');
        });
    }
}
