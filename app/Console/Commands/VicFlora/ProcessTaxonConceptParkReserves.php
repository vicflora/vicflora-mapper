<?php

namespace App\Console\Commands\VicFlora;

use App\Actions\AddIndexesToTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptParkReservesView;
use App\Actions\LoadTaxonConceptAreas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptParkReserves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicflora:process-taxon-concept-park-reserves';

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
        DB::connection('vicflora')->statement("drop view if exists mapper.taxon_concept_park_reserves_view");
        Schema::connection('vicflora')->dropIfExists('mapper.taxon_concept_park_reserves');

        $this->info('Create taxon_concept_park_reserves table');
        (new CreateTaxonConceptAreasTable(connection: 'vicflora'))(layer: 'park_reserves');

        $this->info('Load taxon concept Park and Reserves data');
        (new LoadTaxonConceptAreas(connection: 'vicflora'))(layer: 'park_reserves');
        (new AddIndexesToTaxonConceptAreasTable(connection: 'vicflora'))(layer: 'park_reserves');

        $this->info('Create taxon_concept_park_reserves_view view');
        (new CreateTaxonConceptParkReservesView(connection: 'vicflora'))();
    }
}
