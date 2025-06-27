<?php

namespace App\Console\Commands\VicFunga;

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
    protected $signature = 'vicfunga:get-taxon-data';

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
        (new CreateMapperTaxaTable('vicfunga'))();

        // get VicFunga taxon data
        (new ActionsGetTaxonData('vicfunga'))();

        // match parsed  names to scientific names in VicFunga taxonomy
        (new MatchParsedNames('vicfunga'))();
    }
}
