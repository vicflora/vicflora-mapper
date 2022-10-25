<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetTaxonData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:get-taxon-data';

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
        $getData = new \App\Actions\GetTaxonData;
        $getData();
        return Command::SUCCESS;
    }
}
