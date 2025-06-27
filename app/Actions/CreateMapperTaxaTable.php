<?php

namespace App\Actions;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMapperTaxaTable
{
    private $connection;

    /**
     * Create a new class instance.
     */
    public function __construct(string $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Invoke the class instance.
     */
    public function __invoke(): void
    {
        DB::connection($this->connection)->statement("drop table if exists mapper.taxa cascade");

        Schema::connection($this->connection)->create('mapper.taxa', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestampsTz();
            $table->uuid('scientific_name_id');
            $table->string('scientific_name');
            $table->string('scientific_name_authorship')->nullable();
            $table->string('taxon_rank', 32);
            $table->string('taxonomic_status', 32);
            $table->uuid('species_id');
            $table->string('species_name');
            $table->string('species_name_authorship')->nullable();
            $table->uuid('accepted_name_usage_id');
            $table->string('accepted_name');
            $table->string('accepted_name_authorship')->nullable();
            $table->string('accepted_name_rank', 32);
            $table->string('occurrence_status', 32)->nullable();
            $table->string('establishment_means', 32)->nullable();
            $table->string('degree_of_establishment', 32)->nullable();
            $table->unique('scientific_name_id');
            $table->index('accepted_name_rank');
            $table->index('accepted_name_usage_id');
            $table->index('species_id');
        });
    }
}
