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

class LoadDownloadedData {
    
    public function __invoke(string $table, string $schema='ala')
    {
        $columns = 'uuid,data_resource_uid,collection,catalog_number,unprocessed_scientific_name,latitude,longitude,recorded_by,record_number,event_date,establishment_means,degree_of_establishment,locality,verbatim_locality,reproductive_condition';
        $filename = storage_path("app/ala/$table/data.csv");
        $command = <<<EOT
psql -U vicflora -h postgres-postgis -c "\copy {$schema}.$table ($columns) FROM '$filename' CSV HEADER"
EOT;
        exec($command, $output);
        return 0;
    }
}