<?php

namespace App\Actions\VicFlora;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropIndexesFromOccurrencesTable
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
            if (Schema::connection('vicflora')->hasIndex('mapper.occurrences', 'catalog_number')) {
                $table->dropIndex(['catalog_number']);
            }
            if (Schema::connection('vicflora')->hasIndex('mapper.occurrences', 'establishment_means')) {
                $table->dropIndex(['establishment_means']);
            }
            if (Schema::connection('vicflora')->hasIndex('mapper.occurrences', 'degree_of_establishment')) {
                $table->dropIndex(['degree_of_establishment']);
            }
            if (Schema::connection('vicflora')->hasIndex('mapper.occurrences', 'parsed_name_id')) {
                $table->dropIndex(['parsed_name_id']);
            }
            if (Schema::connection('vicflora')->hasIndex('mapper.occurrences', 'basis_of_record')) {
                $table->dropIndex(['basis_of_record']);
            }
            if (Schema::connection('vicflora')->hasIndex('mapper.occurrences', 'data_source')) {
                $table->dropIndex(['data_source']);
            }
            if (Schema::connection('vicflora')->hasIndex('mapper.occurrences', 'geom')) {
                $table->dropIndex(['geom']);
            }
        });
    }
}
