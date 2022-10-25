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
use Illuminate\Support\Facades\Schema;

class CreateTaxonConceptLocalGovernmentAreasTable {
    
    public function __invoke()
    {
        Schema::create('mapper.taxon_concept_local_government_areas', function(Blueprint $table) {
            $table->timestampsTz();
            $table->uuid('taxon_concept_id');
            $table->integer('local_government_area_id');
            $table->string('occurrence_status', 32);
            $table->string('establishment_means', 32);
            $table->string('degree_of_establishment', 32);
        });
        return 0;
    }
}