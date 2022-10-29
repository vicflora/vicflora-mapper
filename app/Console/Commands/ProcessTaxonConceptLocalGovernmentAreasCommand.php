<?php

namespace App\Console\Commands;

use App\Actions\AddIndexesToTaxonConceptLocalGovernmentAreasTable;
use App\Actions\CreateTaxonConceptLocalGovernmentAreasTable;
use App\Actions\CreateTaxonLocalGovernmentAreasView;
use App\Actions\PopulateTaxonConceptLocalGovernmentAreasTable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptLocalGovernmentAreasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:process-taxon-concept-local-government-areas';

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
        DB::statement('drop view if exists mapper.taxon_local_government_areas_view');
        Schema::dropIfExists('taxon_concept_local_government_areas');

        $this->info('Create taxon_concept_local_government_areas table');
        $createTable = new CreateTaxonConceptLocalGovernmentAreasTable;
        $createTable();


        $this->info('Populate taxon_concept_local_government_areas table');
        $this->info('Start: ' . date('H:i:s'));
        $populate = new PopulateTaxonConceptLocalGovernmentAreasTable;
        $populate();
        $this->info('End: ' . date('H:i:s'));

        $this->info('Add indexes');
        $addIndexes = new AddIndexesToTaxonConceptLocalGovernmentAreasTable;
        $addIndexes();

        $this->info('Create view');
        $createView = new CreateTaxonLocalGovernmentAreasView;
        $createView();

        return Command::SUCCESS;
    }
}
