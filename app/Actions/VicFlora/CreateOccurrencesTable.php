<?php

namespace App\Actions\VicFlora;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOccurrencesTable
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
        if (!Schema::connection('vicflora')->hasTable('mapper.occurrences')) {
            Schema::connection('vicflora')->create('mapper.occurrences', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->timestampsTz();
                $table->string('basis_of_record')->nullable();
                $table->string('data_resource_uid', 16);
                $table->string('collection')->nullable();
                $table->string('catalog_number')->nullable();
                $table->string('scientific_name')->nullable();
                $table->string('recorded_by')->nullable();
                $table->string('record_number')->nullable();
                $table->string('event_date', 32)->nullable();
                $table->string('country')->nullable();
                $table->string('state_province')->nullable();
                $table->text('locality')->nullable();
                $table->text('verbatim_locality')->nullable();
                $table->float('decimal_latitude');
                $table->float('decimal_longitude');
                $table->string('ibra7_region')->nullable();
                $table->string('ibra7_subregion')->nullable();
                $table->string('lga2023')->nullable();
                $table->string('capad2022')->nullable();
                $table->string('rap')->nullable();
                $table->string('establishment_means', 32)->nullable();
                $table->string('degree_of_establishment', 32)->nullable();
                $table->boolean('flowers')->nullable();
                $table->boolean('fruit')->nullable();
                $table->boolean('buds')->nullable();
                $table->magellanPoint('geom', 4326, 'GEOMETRY');
                $table->bigInteger('parsed_name_id')->nullable();
                $table->string('data_source')->nullable();
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
}
