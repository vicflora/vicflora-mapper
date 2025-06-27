<?php

namespace App\Console\Commands\VicFunga;

use App\Actions\AddIndexesToTaxonConceptOccurrencesTable;
use App\Actions\CreateTaxonConceptOccurrencesMaterializedView;
use App\Actions\CreateTaxonConceptOccurrencesView;
use App\Actions\CreateTaxonConceptPhenologyView;
use App\Actions\CreateTaxonOccurrencesTable;
use App\Actions\LoadTaxonConceptOccurrences;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptOccurrences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicfunga:process-taxon-concept-occurrences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::connection('vicfunga')->statement('drop view if exists mapper.taxon_occurrences_view');
        DB::connection('vicfunga')->statement('drop view if exists mapper.taxon_concept_phenology_view');
        DB::connection('vicfunga')->statement('drop view if exists taxon_concept_phenology_view');
        DB::connection('vicfunga')->statement('drop view if exists taxon_concept_last_collected_view');
        DB::connection('vicfunga')->statement('drop materialized view if exists mapper.taxon_occurrences_materialized_view');

        Schema::connection('vicfunga')->dropIfExists('taxon_concept_occurrences');

        $this->info('Create taxon_concept_occurrences table');
        Schema::connection('vicfunga')->dropIfExists('mapper.taxon_concept_occurrences');
        (new CreateTaxonOccurrencesTable(connection: 'vicfunga'))();

        $this->info('Populate taxon_concept_occurrences table');
        $this->info(date('H:i:s'));
        (new LoadTaxonConceptOccurrences(connection: 'vicfunga'))();
        $this->info(date('H:i:s'));

        $this->info('Add indexes');
        (new AddIndexesToTaxonConceptOccurrencesTable(connection: 'vicfunga'))();

        $this->info('Create taxon_occurrences_view');
        DB::connection('vicfunga')->statement('drop view if exists mapper.taxon_occurrences_view');
        (new CreateTaxonConceptOccurrencesView(connection: 'vicfunga'))();

        $this->info('Create taxon_occurrences_materialized_view');
        (new CreateTaxonConceptOccurrencesMaterializedView(connection: 'vicfunga'))();

        $this->info('Create taxon_concept_phenology_view');
        (new CreateTaxonConceptPhenologyView(connection: 'vicfunga'))();
    }
}
