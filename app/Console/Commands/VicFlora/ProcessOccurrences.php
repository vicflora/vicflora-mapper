<?php

namespace App\Console\Commands\vicflora;

use App\Actions\AddRowIndex;
use App\Actions\VicFlora\AddIndexesToOccurrencesTable;
use App\Actions\VicFlora\CreateOccurrencesTable;
use App\Actions\VicFlora\DropIndexesFromOccurrencesTable;
use App\Actions\VicFlora\LoadOccurrences;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;


class ProcessOccurrences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vicflora:process-occurrences {--data-source=} {--page-size=1000}';

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
        $dataSource = Str::upper($this->option('data-source'));
        $table = $this->option('data-source') . '_data';

        (new CreateOccurrencesTable)();
        
        DB::connection('vicflora')->table('mapper.occurrences')
            ->where('data_source', '=', $dataSource)
            ->delete();

        (new AddRowIndex)(connection: 'vicflora', table: "ala.$table");

        (new LoadOccurrences)(table: $table, pageSize: $this->option('page-size'));

        Schema::connection('vicflora')->table("ala.{$table}", function(Blueprint $table) {
            $table->dropColumn('row_index');
        });
    }
}
