<?php

namespace App\Console\Commands;

use App\Actions\AddIndexesToTaxonConceptOccurrencesTable;
use App\Actions\CreateTaxonConceptOccurrencesTable;
use App\Actions\CreateTaxonConceptOccurrencesView;
use App\Actions\CreateTaxonConceptPhenologyView;
use App\Actions\CreateTaxonOccurrencesMaterializedView;
use App\Actions\PopulateTaxonConceptOccurrencesTable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptOccurrencesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:process-taxon-concept-occurrences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::statement('drop view if exists mapper.taxon_occurrences_view');
        DB::statement('drop view if exists mapper.taxon_concept_phenology_view');
        DB::statement('drop view if exists taxon_concept_phenology_view');
        DB::statement('drop materialized view if exists mapper.taxon_occurrences_materialized_view');

        Schema::dropIfExists('taxon_concept_occurrences');

        $this->info('Create taxon_concept_occurrences table');
        $create = new CreateTaxonConceptOccurrencesTable;
        $create();

        $this->info('Populate taxon_concept_occurrences table');
        $this->info(date('H:i:s'));
        $populate = new PopulateTaxonConceptOccurrencesTable;
        $populate();
        $this->info(date('H:i:s'));

        $this->info('Add indexes');
        $addIndexes = new AddIndexesToTaxonConceptOccurrencesTable;
        $addIndexes();

        $this->info('Create taxon_occurrences_view');
        DB::statement('drop view if exists taxon_occurrences_view');
        $createView = new CreateTaxonConceptOccurrencesView;
        $createView();

        $this->info('Create taxon_occurrences_materialized_view');
        $createMaterializedView = new CreateTaxonOccurrencesMaterializedView;
        $createMaterializedView();

        $this->info('Create taxon_concept_phenology_view');
        $createView = new CreateTaxonConceptPhenologyView;
        $createView();

        return Command::SUCCESS;
    }
}
