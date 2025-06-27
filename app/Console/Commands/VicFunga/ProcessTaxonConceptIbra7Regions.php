<?php

namespace App\Console\Commands\VicFunga;

use App\Actions\AddIndexesToTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptIbra7RegionsView;
use App\Actions\LoadTaxonConceptAreas;
use App\Actions\LoadTaxonConceptOccurrences;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptIbra7Regions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicfunga:process-taxon-concept-ibra7-regions';

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
        DB::connection('vicfunga')->statement("drop view if exists mapper.taxon_concept_ibra7_regions_view");
        Schema::connection('vicfunga')->dropIfExists('mapper.taxon_concept_ibra7_regions');

        $this->info('Create taxon_concept_ibra7_regions table');
        (new CreateTaxonConceptAreasTable(connection: 'vicfunga'))(layer: 'ibra7_regions');

        $this->info('Load taxon concept IBRA 7 region data');
        (new LoadTaxonConceptAreas(connection: 'vicfunga'))(layer: 'ibra7_regions');
        (new AddIndexesToTaxonConceptAreasTable(connection: 'vicfunga'))(layer: 'ibra7_regions');

        $this->info('Create taxon_concept_ibra7_regions_view view');
        (new CreateTaxonConceptIbra7RegionsView(connection: 'vicfunga'))();
    }
}
