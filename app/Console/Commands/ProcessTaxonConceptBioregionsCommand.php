<?php

namespace App\Console\Commands;

use App\Actions\AddIndexesToTaxonConceptBioregionsTable;
use App\Actions\CreateTaxonBioregionsView;
use App\Actions\CreateTaxonConceptBioregionsTable;
use App\Actions\PopulateTaxonConceptBioregionsTable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptBioregionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:process-taxon-concept-bioregions';

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
        DB::statement('drop view if exists mapper.taxon_bioregions_view');
        Schema::dropIfExists('taxon_concept_bioregions');

        $this->info('Create taxon_concept_bioregions table');
        $createTable = new CreateTaxonConceptBioregionsTable;
        $createTable();


        $this->info('Populate taxon_concept_bioregions table');
        $this->info('Start: ' . date('H:i:s'));
        $populate = new PopulateTaxonConceptBioregionsTable;
        $populate();
        $this->info('End: ' . date('H:i:s'));

        $this->info('Add indexes');
        $addIndexes = new AddIndexesToTaxonConceptBioregionsTable;
        $addIndexes();

        $this->info('Create view');
        $createView = new CreateTaxonBioregionsView;
        $createView();

        return Command::SUCCESS;
    }
}
