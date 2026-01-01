<?php

namespace App\Console\Commands;

use App\Actions\AddIndexesToTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptLocalGovernmentAreasView;
use App\Actions\LoadTaxonConceptAreas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptLocalGovernmentAreas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicflora:process-taxon-concept-local-government-areas';

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
        DB::connection('vicflora')->statement("drop view if exists mapper.taxon_concept_local_government_areas_view");
        Schema::connection('vicflora')->dropIfExists('mapper.taxon_concept_local_government_areas');

        $this->info('Create taxon_concept_local_government_areas table');
        (new CreateTaxonConceptAreasTable(connection: 'vicflora'))(layer: 'local_government_areas');

        $this->info('Load taxon concept Local Government Area data');
        (new LoadTaxonConceptAreas(connection: 'vicflora'))(layer: 'local_government_areas');
        (new AddIndexesToTaxonConceptAreasTable(connection: 'vicflora'))(layer: 'local_government_areas');

        $this->info('Create taxon_concept_local_government_areas_view view');
        (new CreateTaxonConceptLocalGovernmentAreasView(connection: 'vicflora'))();

    }
}
