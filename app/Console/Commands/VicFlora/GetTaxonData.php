<?php

namespace App\Console\Commands\vicflora;

use App\Actions\CreateMapperTaxaTable;
use App\Actions\GetTaxonData as ActionsGetTaxonData;
use App\Actions\MatchParsedNames;
use Illuminate\Console\Command;

class GetTaxonData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicflora:get-taxon-data';

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
        // create mapper.taxa table
        (new CreateMapperTaxaTable('vicflora'))();

        // get vicflora taxon data
        (new ActionsGetTaxonData('vicflora'))();

        // match parsed  names to scientific names in vicflora taxonomy
        (new MatchParsedNames('vicflora'))();
    }
}
