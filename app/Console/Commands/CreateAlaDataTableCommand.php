<?php

namespace App\Console\Commands;

use App\Actions\CreateAlaDataTable;
use Illuminate\Console\Command;

class CreateAlaDataTableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:create-ala-data-table {--dataset=avh}';

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
        $createTable = new CreateAlaDataTable;
        $createTable($this->option('dataset'));
        return Command::SUCCESS;
    }
}
