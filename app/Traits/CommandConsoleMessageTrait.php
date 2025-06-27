<?php

namespace App\Traits;

use DateTime;

trait CommandConsoleMessageTrait
{

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
    }}
