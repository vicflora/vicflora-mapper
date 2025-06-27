<?php

namespace App\Actions;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToTaxonConceptAreasTable
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
    public function __invoke(string $layer): void
    {
        Schema::connection($this->connection)->table('mapper.taxon_concept_' . $layer, function (Blueprint $table) {
            $table->index('taxon_concept_id');
            $table->index('area_id');
        });

    }
}
