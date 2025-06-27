<?php

namespace App\Actions\VicFlora;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAlaDataTable
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
    public function __invoke(string $table): void
    {
        DB::connection('vicflora')->statement("drop table if exists ala.{$table}_old");

        if (Schema::connection('vicflora')->hasTable("ala.$table")) {
            DB::connection('vicflora')->statement("alter table ala.$table rename to {$table}_old");
        }

        Schema::connection('vicflora')->create("ala.$table", function (Blueprint $table) {
            $table->uuid('uuid');
            $table->timestampsTz();
            $table->string('data_resource_uid', 16)->nullable();
            $table->string('basis_of_record')->nullable();
            $table->string('collection')->nullable();
            $table->string('catalog_number');
            $table->string('unprocessed_scientific_name')->nullable();
            $table->string('recorded_by')->nullable();
            $table->string('record_number')->nullable();
            $table->string('event_date', 32)->nullable();
            $table->double('latitude');
            $table->double('longitude');
            $table->string('country')->nullable();
            $table->string('state_province')->nullable();
            $table->text('locality')->nullable();
            $table->text('verbatim_locality')->nullable();
            $table->string('ibra7_region')->nullable();
            $table->string('ibra7_subregion')->nullable();
            $table->string('lga2023')->nullable();
            $table->string('capad2022')->nullable();
            $table->string('establishment_means')->nullable();
            $table->string('degree_of_establishment', 32)->nullable();
            $table->string('reproductive_condition')->nullable();
            $table->bigInteger('parsed_name_id')->nullable();
        });
    }
}
