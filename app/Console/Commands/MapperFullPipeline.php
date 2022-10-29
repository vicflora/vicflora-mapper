<?php

namespace App\Console\Commands;

use App\Traits\CommandConsoleMessageTrait;
use Illuminate\Console\Command;

class MapperFullPipeline extends Command
{
    use CommandConsoleMessageTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:full-pipeline';

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
        $this->info('Download data');
        $tasks = [
            [
                'message' => 'Get AVH data',
                'command' => function() {
                    $this->callSilent('mapper:get-ala-data');
                }
            ],
            [
                'message' => 'Get VBA data',
                'command' => function() {
                    $this->callSilent('mapper:get-ala-data', 
                            ['--dataset' => 'vba']);
                }
            ],
            [
                'message' => 'Process AVH occurrences',
                'command' => function() {
                    $this->callSilent('mapper:process-occurrences');
                }
            ],
            [
                'message' => 'Process VBA occurrences',
                'command' => function() {
                    $this->callSilent('mapper:process-occurrences', 
                            ['--dataset' => 'vba']);
                }
            ],
            [
                'message' => 'Process taxon occurrences',
                'command' => function() {
                    $this->callSilent('mapper:process-taxon-concept-occurrences');
                }
            ],
            [
                'message' => 'Process taxon bioregions',
                'command' => function() {
                    $this->callSilent('mapper:process-taxon-concept-bioregions');
                }
            ],
            [
                'message' => 'Process taxon Local Government Areas',
                'command' => function() {
                    $this->callSilent('mapper:process-taxon-concept-local-government-areas');
                }
            ],
            [
                'message' => 'Process taxon parks and reserves',
                'command' => function() {
                    $this->callSilent('mapper:process-taxon-concept-park-reserves');
                }
            ],
            [
                'message' => 'Process taxon Registered Aboriginal Parties',
                'command' => function() {
                    $this->callSilent('mapper:process-taxon-concept-raps');
                }
            ],
        ];

        $this->runTasks($tasks);

        return Command::SUCCESS;
    }
}
