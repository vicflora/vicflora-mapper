<?php

namespace App\Console\Commands;

use App\Actions\CreateAlaDataTable;
use Illuminate\Console\Command;

class CreateAvhDataTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:create-avh-data-table';

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
        $createTable('avh_data');
        return Command::SUCCESS;
    }
}
