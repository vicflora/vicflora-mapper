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
        Schema::create('parsed_names', function (Blueprint $table) {
            $table->id();
            $table->timestampsTz();
            $table->string('scientific_name');
            $table->string('type', 32);
            $table->boolean('authors_parsed')->nullable();
            $table->string('genus_or_above', 64)->nullable();
            $table->string('infrageneric', 64)->nullable();
            $table->string('specific_epithet', 64)->nullable();
            $table->string('infraspecific_epithet', 64)->nullable();
            $table->string('cultivar_epithet', 64)->nullable();
            $table->string('strain', 64)->nullable();
            $table->string('notho', 64)->nullable();
            $table->string('rank_marker', 32)->nullable();
            $table->string('authorship', 128)->nullable();
            $table->string('bracket_authorship', 128)->nullable();
            $table->string('year', 16)->nullable();
            $table->string('bracket_year', 16)->nullable();
            $table->string('sensu', 128)->nullable();
            $table->string('nom_status', 32)->nullable();
            $table->string('canonical_name', 128)->nullable();
            $table->string('canonical_name_with_marker', 128)->nullable();
            $table->string('canonical_name_complete')->nullable();
            $table->uuid('vicflora_scientific_name_id')->nullable();
            $table->string('name_match_type', 40)->nullable();
            $table->index('canonical_name_complete');
            $table->index('canonical_name_with_marker');
            $table->index('name_match_type');
            $table->index('scientific_name');
            $table->index('vicflora_scientific_name_id');
            $table->foreign('vicflora_scientific_name_id')
                    ->references('scientific_name_id')->on('taxa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parsed_names');
    }
};
