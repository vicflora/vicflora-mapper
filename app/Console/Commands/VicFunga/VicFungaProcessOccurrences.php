<?php

namespace App\Console\Commands\VicFunga;

use App\Actions\AddRowIndex;
use App\Actions\VicFunga\AddIndexesToOccurrencesTable;
use App\Actions\VicFunga\CreateOccurrencesTable;
use App\Actions\VicFunga\LoadOccurrences;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VicFungaProcessOccurrences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicfunga:process-occurrences {--page-size=1000}';

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
        DB::connection('vicfunga')->statement("drop table if exists mapper.occurrences cascade");
        
        (new CreateOccurrencesTable)();

        (new AddRowIndex)(connection: 'vicfunga', table: 'ala.fungi_data');

        (new LoadOccurrences)(pageSize: $this->option('page-size'));

        Schema::connection('vicfunga')->table('ala.fungi_data', function(Blueprint $table) {
            $table->dropColumn('row_index');
        });

        (new AddIndexesToOccurrencesTable)();
    }
}
