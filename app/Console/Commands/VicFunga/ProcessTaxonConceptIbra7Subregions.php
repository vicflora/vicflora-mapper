<?php

namespace App\Console\Commands\VicFunga;

use App\Actions\AddIndexesToTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptIbra7SubregionsView;
use App\Actions\LoadTaxonConceptAreas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptIbra7Subregions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicfunga:process-taxon-concept-ibra7-subregions';

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
        DB::connection('vicfunga')->statement("drop view if exists mapper.taxon_concept_ibra7_subregions_view");
        Schema::connection('vicfunga')->dropIfExists('mapper.taxon_concept_ibra7_subregions');

        $this->info('Create taxon_concept_ibra7_subregions table');
        (new CreateTaxonConceptAreasTable(connection: 'vicfunga'))(layer: 'ibra7_subregions');

        $this->info('Load taxon concept IBRA 7 subregion data');
        (new LoadTaxonConceptAreas(connection: 'vicfunga'))(layer: 'ibra7_subregions');
        (new AddIndexesToTaxonConceptAreasTable(connection: 'vicfunga'))(layer: 'ibra7_subregions');

        $this->info('Create taxon_concept_ibra7_subregions_view view');
        (new CreateTaxonConceptIbra7SubregionsView(connection: 'vicfunga'))();
    }
}
