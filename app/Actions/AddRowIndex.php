<?php

namespace App\Actions;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRowIndex
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Invoke the class instance.
     */
    public function __invoke(string $connection, string $table): void
    {
        if (!Schema::connection($connection)->hasColumn($table, 'row_index')) {
            Schema::connection($connection)->table($table, function (Blueprint $table) {
                $table->bigIncrements('row_index');
            });
        }
    }
}
