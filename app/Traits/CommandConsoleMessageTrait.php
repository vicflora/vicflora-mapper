<?php
// Copyright 2022 Royal Botanic Gardens Board
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

namespace App\Traits;

use DateTime;

trait CommandConsoleMessageTrait {

    /**
     * Creates string of given width and optional line break
     *
     * @param string $str
     * @param integer $width
     * @param boolean $linebreak
     * @return string
     */
    function column(string $str, int $width=30, bool $linebreak=false): string
    {
        return $str . str_repeat(' ', $width - strlen($str)) .
                ($linebreak ? "\n" : '');
    }

    /**
     * Prints table header with column headings
     *
     * @return void
     */
    function header()
    {
        echo str_repeat('-', 120) . "\n" .
                $this->column('  Start', 30) .
                $this->column('Task', 80) .
                $this->column('Duration', 10, true) .
                str_repeat('-', 120) . "\n" ;
    }

    /**
     * Executes supplied command and creates table row with start time, message
     * and duration
     *
     * @param callable $command
     * @param string $message
     * @return void
     */
    function row(callable $command, string $message)
    {
        $start = new DateTime();
        echo $this->column('  ' . $start->format('Y-m-d H:i:s'), 30) .
                $this->column($message, 80);
        $command();
        $end = new DateTime();
        echo $this->column($start->diff($end)->format('%H:%I:%S'), 30, true);
    }

    /**
     * Prints  table footer with total execution time
     *
     * @param DateTime $start
     * @param DateTime $end
     * @return void
     */
    function footer(DateTime $start, DateTime $end)
    {
        echo str_repeat('-', 120) . "\n" .
            $this->column('  Total execution time:', 110) .
                $this->column($start->diff($end)->format('%H:%I:%S'), 30, true)
                . str_repeat('-', 120)
                . "\n\n";
    }

    function runTasks(array $tasks)
    {
        $start = new DateTime();
        $this->header();
        foreach ($tasks as $task) {
            $this->row($task['command'], $task['message']);
        }
        $end = new DateTime();
        $this->footer($start, $end);
    }
}
