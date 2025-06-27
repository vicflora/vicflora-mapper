<?php

namespace App\Console\Commands\vicflora;

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
    protected $signature = 'vicflora:mapper-full-pipeline {--avh} {--vba}';

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
        if ($this->option('avh')) {
            $tasks = array_merge($tasks, [
                [
                    'message' => 'Get AVH data',
                    'command' => function() {
                        $this->callSilent('vicflora:get-ala-data', ['--data-source' => 'avh']);
                    }
                ],
                [
                    'message' => 'Process AVH occurrences',
                    'command' => function() {
                        $this->callSilent('vicflora:process-occurrences', ['--data-source' => 'avh']);
                    }
                ],
                [
                    'message' => 'Match new name strings',
                    'command' => function() {
                        $this->callSilent('app:parse-names', ['--database' => 'vicflora']);
                    }
                ],
            ]);
        }

        if ($this->option('vba')) {
            $tasks = array_merge($tasks, [
                [
                    'message' => 'Get VBA data',
                    'command' => function() {
                        $this->callSilent('vicflora:get-ala-data', ['--data-source' => 'vba']);
                    }
                ],
                [
                    'message' => 'Process VBA occurrences',
                    'command' => function() {
                        $this->callSilent('vicflora:process-occurrences', ['--data-source' => 'vba']);
                    }
                ],
                [
                    'message' => 'Match new name strings',
                    'command' => function() {
                        $this->callSilent('app:parse-names', ['--database' => 'vicflora']);
                    }
                ],
            ]);
        }

        // process occurrences and map against VicFlora concepts
        $tasks = array_merge($tasks, [
            [
                'message' => 'Get taxon data',
                'command' => function() {
                    $this->callSilent('vicflora:get-taxon-data');
                }
            ],
            [
                'message' => 'Process taxon occurrences',
                'command' => function() {
                    $this->callSilent('vicflora:process-taxon-concept-occurrences');
                }
            ],
            [
                'message' => 'Process taxon concept Bioregions',
                'command' => function() {
                    $this->callSilent('vicflora:process-taxon-concept-bioregions');
                }
            ],
            [
                'message' => 'Process taxon concept Local Government Areas',
                'command' => function() {
                    $this->callSilent('vicflora:process-taxon-concept-local-government-areas');
                }
            ],
            [
                'message' => 'Process taxon concept Parks and Reserves',
                'command' => function() {
                    $this->callSilent('vicflora:process-taxon-concept-park-reserves');
                }
            ],
            [
                'message' => 'Process taxon concept RAPs',
                'command' => function() {
                    $this->callSilent('vicflora:process-taxon-concept-raps');
                }
            ],
        ]);

        $this->runTasks($tasks);
    }
}
