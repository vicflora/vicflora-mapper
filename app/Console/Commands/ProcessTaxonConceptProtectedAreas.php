<?php

namespace App\Console\Commands;

use App\Actions\AddIndexesToTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptProtectedAreasView;
use App\Actions\LoadTaxonConceptAreas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptProtectedAreas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicflora:process-taxon-concept-protected-areas';

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
        DB::connection('vicflora')->statement("drop view if exists mapper.taxon_concept_protected_areas_view");
        Schema::connection('vicflora')->dropIfExists('mapper.taxon_concept_protected_areas');

        $this->info('Create taxon_concept_protected_areas table');
        (new CreateTaxonConceptAreasTable(connection: 'vicflora'))(layer: 'protected_areas');

        $this->info('Load taxon concept Protected Area data');
        (new LoadTaxonConceptAreas(connection: 'vicflora'))(layer: 'protected_areas');
        (new AddIndexesToTaxonConceptAreasTable(connection: 'vicflora'))(layer: 'protected_areas');

        $this->info('Create taxon_concept_protected_areas_view view');
        (new CreateTaxonConceptProtectedAreasView(connection: 'vicflora'))();
    }
}
