<?php

namespace App\Console\Commands;

use App\Actions\VicFlora\CreateAlaDataTable;
use App\Actions\VicFlora\DownloadOccurrenceData;
use App\Actions\LoadDownloadedData;
use Illuminate\Console\Command;

class GetAlaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicflora:get-ala-data {--data-source=avh}';

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

        $dataset = $datasets[$this->option('data-source')];


        $this->info('Download Occurrence data...');

        (new DownloadOccurrenceData)(q: $dataset['query'], table: $dataset['table']);

        $this->info('upload downloaded data to database...');

        (new CreateAlaDataTable)(table: $dataset['table']);

        $filename = storage_path("app/private/ala/{$dataset['table']}/data.csv");

        $columns = [
            'uuid',
            'data_resource_uid',
            'basis_of_record',
            'collection',
            'catalog_number',
            'unprocessed_scientific_name',
            'latitude',
            'longitude',
            'recorded_by',
            'record_number',
            'event_date',
            'establishment_means',
            'degree_of_establishment',
            'country',
            'state_province',
            'locality',
            'verbatim_locality',
            'reproductive_condition',
            'capad2022',
            'lga2023',
            'ibra7_region',
            'ibra7_subregion',
        ];

        (new LoadDownloadedData)(
            filename: $filename, 
            columns: $columns, 
            connection: 'vicflora',
            table: $dataset['table']
        );
    }
}
