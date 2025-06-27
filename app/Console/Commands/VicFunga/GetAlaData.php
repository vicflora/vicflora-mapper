<?php

namespace App\Console\Commands\VicFunga;

use App\Actions\VicFunga\CreateAlaDataTable;
use App\Actions\VicFunga\DownloadOccurrenceData;
use App\Actions\LoadDownloadedData;
use Illuminate\Console\Command;

class GetAlaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicfunga:get-ala-data';

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
        $this->info('Download Occurrence data...');

        (new DownloadOccurrenceData)();

        $this->info('upload downloaded data to database...');

        (new CreateAlaDataTable)();

        $filename = storage_path("app/private/ala/fungi_data/data.csv");

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

        (new LoadDownloadedData)($filename, $columns, 'vicfunga');
    }
}
