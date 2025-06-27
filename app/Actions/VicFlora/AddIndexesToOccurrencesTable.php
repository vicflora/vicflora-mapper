<?php

namespace App\Actions\VicFlora;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToOccurrencesTable
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Invoke the class instance.
     */
    public function __invoke(): void
    {
        Schema::connection('vicflora')->table('mapper.occurrences', function (Blueprint $table) {
            $table->index('catalog_number');
            $table->index('establishment_means');
            $table->index('degree_of_establishment');
            $table->index('parsed_name_id');
            $table->index('basis_of_record');
            $table->index('data_source');
            $table->spatialIndex('geom');
        });
    }
}
