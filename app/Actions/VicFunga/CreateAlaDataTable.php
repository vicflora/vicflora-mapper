<?php

namespace App\Actions\VicFunga;

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
    public function __invoke(): void
    {
        DB::connection('vicfunga')->statement("drop table if exists ala.fungi_data_old");

        if (Schema::connection('vicfunga')->hasTable("ala.fungi_data")) {
            DB::connection('vicfunga')->statement("alter table ala.fungi_data rename to fungi_data_old");
        }

        Schema::connection('vicfunga')->create('ala.fungi_data', function (Blueprint $table) {
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
