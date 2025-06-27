<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assertions', function (Blueprint $table) {
            $table->id();
            $table->timestampsTz();
            $table->uuid('occurrence_id');
            $table->string('reason')->nullable();
            $table->text('remarks')->nullable();
            $table->string('term', 32);
            $table->string('asserted_value', 32);
            $table->bigInteger('agent_id')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assertions');
    }
};
