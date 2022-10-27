<?php

namespace App\Console\Commands;

use App\Actions\CreateAlaDataTable;
use App\Actions\DownloadOccurrenceData;
use App\Actions\LoadDownloadedData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetAlaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:get-ala-data {--dataset=avh}';

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

        $datasets = [
            'avh' => [
                'table' => 'avh_data',
                'query' => 'data_hub_uid:dh9',
            ],
            'vba' => [
                'table' => 'vba_data',
                'query' => 'data_resource_uid:dr1097'
            ],
        ];

        $dataset = $datasets[$this->option('dataset')];

        $download = new DownloadOccurrenceData;
        $download($dataset['query'], $dataset['table']);


        $createTable = new CreateAlaDataTable;
        $createTable($dataset['table']);

        $loadData = new LoadDownloadedData;
        $loadData($dataset['table']);

        return Command::SUCCESS;
    }
}
