<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateSolrIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:update-solr-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update SOLR index';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $command = "php ../vicflora-worker/artisan solr:full-update";
        passthru($command);
        return Command::SUCCESS;
    }
}
