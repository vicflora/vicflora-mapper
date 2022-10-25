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
        Schema::create('taxa', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestampsTz();
            $table->uuid('scientific_name_id');
            $table->string('scientific_name', 128);
            $table->string('scientific_name_authorship', 128)->nullable();
            $table->string('taxon_rank', 32);
            $table->string('taxonomic_status', 32);
            $table->uuid('species_id');
            $table->string('species_name', 128);
            $table->string('species_name_authorship', 128)->nullable();
            $table->uuid('accepted_name_usage_id');
            $table->string('accepted_name', 128);
            $table->string('accepted_name_authorship', 128)->nullable();
            $table->string('accepted_name_rank', 32);
            $table->string('occurrence_status', 32)->nullable();
            $table->string('establishment_means', 32)->nullable();
            $table->string('degree_of_establishment', 32)->nullable();
            $table->index('accepted_name_usage_id');
            $table->unique('scientific_name_id');
            $table->index('species_id');
            $table->index('accepted_name_rank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxa');
    }
};
