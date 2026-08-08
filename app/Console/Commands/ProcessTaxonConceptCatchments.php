<?php

namespace App\Console\Commands;

use App\Actions\AddIndexesToTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptAreasTable;
use App\Actions\LoadTaxonConceptAreas;
use App\Actions\VicFlora\CreateTaxonConceptCatchmentsView;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptCatchments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicflora:process-taxon-concept-catchments';

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
        DB::connection('vicflora')->statement("drop view if exists mapper.taxon_concept_catchments_view");
        Schema::connection('vicflora')->dropIfExists('mapper.taxon_concept_catchments');

        $this->info('Create taxon_concept_catchments table');
        (new CreateTaxonConceptAreasTable(connection: 'vicflora'))(layer: 'catchments');

        $this->info('Load taxon concept Catchments data');
        (new LoadTaxonConceptAreas(connection: 'vicflora'))(layer: 'catchments');
        (new AddIndexesToTaxonConceptAreasTable(connection: 'vicflora'))(layer: 'catchments');

        $this->info('Create taxon_concept_catchments_view view');
        (new CreateTaxonConceptCatchmentsView(connection: 'vicflora'))();
    }
}
