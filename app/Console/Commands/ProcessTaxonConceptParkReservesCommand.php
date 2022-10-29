<?php

namespace App\Console\Commands;

use App\Actions\AddIndexesToTaxonConceptParkReservesTable;
use App\Actions\CreateTaxonConceptParkReservesTable;
use App\Actions\CreateTaxonParkReservesView;
use App\Actions\PopulateTaxonConceptParkReservesTable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptParkReservesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:process-taxon-concept-park-reserves';

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
        DB::statement('drop view if exists mapper.taxon_park_reserves_view');
        Schema::dropIfExists('taxon_concept_park_reserves');

        $this->info('Create taxon_concept_park_reserves table');
        $createTable = new CreateTaxonConceptParkReservesTable;
        $createTable();


        $this->info('Populate taxon_concept_park_reserves table');
        $this->info('Start: ' . date('H:i:s'));
        $populate = new PopulateTaxonConceptParkReservesTable;
        $populate();
        $this->info('End: ' . date('H:i:s'));

        $this->info('Add indexes');
        $addIndexes = new AddIndexesToTaxonConceptParkReservesTable;
        $addIndexes();

        $this->info('Create view');
        $createView = new CreateTaxonParkReservesView;
        $createView();

        return Command::SUCCESS;
    }
}
