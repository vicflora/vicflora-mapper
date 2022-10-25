<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('occurrences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestampsTz();
            $table->string('data_resource_uid', 16);
            $table->string('collection')->nullable();
            $table->string('catalog_number', 32);
            $table->string('scientific_name')->nullable();
            $table->string('recorded_by')->nullable();
            $table->string('record_number', 64)->nullable();
            $table->string('event_date', 32)->nullable();
            $table->text('locality')->nullable();
            $table->text('verbatim_locality')->nullable();
            $table->float('decimal_latitude');
            $table->float('decimal_longitude');
            $table->string('establishment_means', 32)->nullable();
            $table->string('degree_of_establishment', 32)->nullable();
            $table->boolean('flowers')->nullable();
            $table->boolean('fruit')->nullable();
            $table->boolean('buds')->nullable();
            $table->point('geom', 'GEOMETRY', 4326)->nullable();
            $table->bigInteger('parsed_name_id')->nullable();
            $table->index('catalog_number');
            $table->index('establishment_means');
            $table->index('degree_of_establishment');
            $table->index('parsed_name_id');
            $table->foreign('parsed_name_id')->references('id')
                    ->on('parsed_names');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('occurrences');
    }
};
