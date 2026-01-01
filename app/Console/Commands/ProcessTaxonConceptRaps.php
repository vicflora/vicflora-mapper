<?php

namespace App\Console\Commands;

use App\Actions\AddIndexesToTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptAreasTable;
use App\Actions\CreateTaxonConceptRapsView;
use App\Actions\LoadTaxonConceptAreas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptRaps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicflora:process-taxon-concept-raps';

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
        DB::connection('vicflora')->statement("drop view if exists mapper.taxon_concept_raps_view");
        Schema::connection('vicflora')->dropIfExists('mapper.taxon_concept_raps');

        $this->info('Create taxon_concept_raps table');
        (new CreateTaxonConceptAreasTable(connection: 'vicflora'))(layer: 'raps');

        $this->info('Load taxon concept RAPS data');
        (new LoadTaxonConceptAreas(connection: 'vicflora'))(layer: 'raps');
        (new AddIndexesToTaxonConceptAreasTable(connection: 'vicflora'))(layer: 'raps');

        $this->info('Create taxon_concept_raps_view view');
        (new CreateTaxonConceptRapsView(connection: 'vicflora'))();
    }
}
