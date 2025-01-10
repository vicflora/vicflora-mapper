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

namespace App\Actions;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAlaDataTable {
    
    public function __invoke(string $table, string $schema='ala')
    {
        DB::statement("drop table if exists $schema.{$table}_old");

        if (Schema::hasTable("$schema.$table")) {
            DB::statement("alter table $schema.$table rename to {$table}_old");
        }

        Schema::create($schema . '.' . $table, function (Blueprint $table) {
            $table->uuid('uuid');
            $table->timestampsTz();
            $table->string('data_resource_uid', 16)->nullable();
            $table->string('collection')->nullable();
            $table->string('catalog_number', 32);
            $table->string('unprocessed_scientific_name')->nullable();
            $table->string('recorded_by')->nullable();
            $table->string('record_number', 64)->nullable();
            $table->string('event_date', 32)->nullable();
            $table->double('latitude');
            $table->double('longitude');
            $table->text('locality')->nullable();
            $table->text('verbatim_locality')->nullable();
            $table->string('establishment_means')->nullable();
            $table->string('degree_of_establishment', 32)->nullable();
            $table->string('reproductive_condition')->nullable();
            $table->bigInteger('parsed_name_id')->nullable();
        });
        return 0;
    }
}