<?php

namespace App\Console\Commands;

use App\Actions\ProcessOccurrences;
use Illuminate\Console\Command;

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
        $process = new ProcessOccurrences;
        $process($this->option('dataset') . '_data');
        return Command::SUCCESS;
    }
}
