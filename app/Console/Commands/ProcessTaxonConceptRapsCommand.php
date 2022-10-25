<?php

namespace App\Console\Commands;

use App\Actions\AddIndexesToTaxonConceptRapsTable;
use App\Actions\CreateTaxonConceptRapsTable;
use App\Actions\CreateTaxonRapsView;
use App\Actions\PopulateTaxonConceptRapsTable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessTaxonConceptRapsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:process-taxon-concept-raps';

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
        DB::statement('drop view if exists mapper.taxon_raps_view');
        Schema::dropIfExists('taxon_concept_raps');

        $this->info('Create taxon_concept_raps table');
        $createTable = new CreateTaxonConceptRapsTable;
        $createTable();

        $this->info('Populate taxon_concept_raps table');
        $this->info('Start: ' . date('H:i:s'));
        $populate = new PopulateTaxonConceptRapsTable;
        $populate();
        $this->info('End: ' . date('H:i:s'));

        $this->info('Add indexes');
        $addIndexes = new AddIndexesToTaxonConceptRapsTable;
        $addIndexes();

        $this->info('Create view');
        $createView = new CreateTaxonRapsView;
        $createView();

        return Command::SUCCESS;
    }
}
