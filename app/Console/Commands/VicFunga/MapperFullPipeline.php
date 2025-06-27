<?php

namespace App\Console\Commands\VicFunga;

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
    protected $signature = 'vicfunga:mapper-full-pipeline {--download}';

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
        $this->info('Download data');

        $tasks = [];

        // if the --avh option is set, get new AVH data
        if ($this->option('download')) {
            $tasks = array_merge($tasks, [
                [
                    'message' => 'Get ALA data',
                    'command' => function() {
                        $this->callSilent('vicfunga:get-ala-data');
                    }
                ],
                [
                    'message' => 'Process occurrences',
                    'command' => function() {
                        $this->callSilent('vicfunga:process-occurrences');
                    }
                ],
                [
                    'message' => 'Match new name strings',
                    'command' => function() {
                        $this->callSilent('app:parse-names', ['--database' => 'vicfunga']);
                    }
                ],
            ]);
        }

        // process occurrences and map against VicFlora concepts
        $tasks = array_merge($tasks, [
            [
                'message' => 'Get taxon data',
                'command' => function() {
                    $this->callSilent('vicfunga:get-taxon-data');
                }
            ],
            [
                'message' => 'Process taxon occurrences',
                'command' => function() {
                    $this->callSilent('vicfunga:process-taxon-concept-occurrences');
                }
            ],
            [
                'message' => 'Process taxon concept IBRA 7 regions',
                'command' => function() {
                    $this->callSilent('vicfunga:process-taxon-concept-ibra7-regions');
                }
            ],
            [
                'message' => 'Process taxon concept IBRA 7 subregions',
                'command' => function() {
                    $this->callSilent('vicfunga:process-taxon-concept-ibra7-subregions');
                }
            ],
            [
                'message' => 'Process taxon concept Local Government Areas',
                'command' => function() {
                    $this->callSilent('vicfunga:process-taxon-concept-local-government-areas');
                }
            ],
            [
                'message' => 'Process taxon concept Protected Areas',
                'command' => function() {
                    $this->callSilent('vicfunga:process-taxon-concept-protected-areas');
                }
            ],
        ]);

        $this->runTasks($tasks);
    }
}
