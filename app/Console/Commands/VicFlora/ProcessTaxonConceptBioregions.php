<?php

namespace App\Console\Commands\VicFlora;

use App\Actions\AddIndexesToTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptBioregionsView;
use App\Actions\LoadTaxonConceptAreas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptBioregions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicflora:process-taxon-concept-bioregions';

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
        DB::connection('vicflora')->statement("drop view if exists mapper.taxon_concept_bioregions_view");
        Schema::connection('vicflora')->dropIfExists('mapper.taxon_concept_bioregions');

        $this->info('Create taxon_concept_bioregions table');
        (new CreateTaxonConceptAreasTable(connection: 'vicflora'))(layer: 'bioregions');

        $this->info('Load taxon concept Bioregions data');
        (new LoadTaxonConceptAreas(connection: 'vicflora'))(layer: 'bioregions');
        (new AddIndexesToTaxonConceptAreasTable(connection: 'vicflora'))(layer: 'bioregions');

        $this->info('Create taxon_concept_bioregions_view view');
        (new CreateTaxonConceptBioregionsView(connection: 'vicflora'))();
    }
}
