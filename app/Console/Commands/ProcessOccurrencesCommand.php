<?php

namespace App\Console\Commands;

use App\Actions\ProcessOccurrences;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessOccurrencesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:process-occurrences {--dataset=avh}';

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
        $dataSource = strtoupper($this->option('dataset'));
        DB::statement("delete from mapper.occurrences where data_source='$dataSource'");

        $process = new ProcessOccurrences;
        $process($dataSource);
        return Command::SUCCESS;
    }
}
