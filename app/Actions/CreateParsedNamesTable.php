<?php

namespace App\Actions;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParsedNamesTable
{
    /**
     * Invoke the class instance.
     */
    public function __invoke(string $connection)
    {
        if (!Schema::connection($connection)->hasTable('mapper.parsed_names')) {
            Schema::connection($connection)->create('mapper.parsed_names', function (Blueprint $table) {
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
                $table->string('notho', 32)->nullable();
                $table->string('rank_marker', 64)->nullable();
                $table->string('authorship')->nullable();
                $table->string('bracket_authorship')->nullable();
                $table->string('year', 16)->nullable();
                $table->string('bracket_year', 16)->nullable();
                $table->string('sensu', 128)->nullable();
                $table->boolean('parsed')->nullable();
                $table->boolean('parsed_partially')->nullable();
                $table->string('key', 128)->nullable();
                $table->string('nom_status', 32)->nullable();
                $table->string('canonical_name', 128)->nullable();
                $table->string('canonical_name_with_marker', 128)->nullable();
                $table->string('canonical_name_complete')->nullable();
                $table->text('remarks')->nullable();
                $table->uuid('vicflora_scientific_name_id')->nullable();
                $table->string('name_match_type')->nullable();
                $table->index('canonical_name_complete');
                $table->index('canonical_name_with_marker');
                $table->index('name_match_type');
                $table->index('scientific_name');
                $table->index('vicflora_scientific_name_id');
            });
        }
    }
}
