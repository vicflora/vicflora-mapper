<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class MatchParsedNames
{
    private $connection;

    /**
     * Create a new class instance.
     */
    public function __construct(string $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Invoke the class instance.
     */
    public function __invoke(): void
    {
        // reset
        $sql = <<<SQL
update mapper.parsed_names pn 
set vicflora_scientific_name_id = null, name_match_type = null
SQL;
        DB::connection($this->connection)->update($sql);

        // exact matches
        $sql = <<<SQL
update mapper.parsed_names pn 
set vicflora_scientific_name_id = t.scientific_name_id, name_match_type = 'exactMatch'
from mapper.taxa t 
where (pn.scientific_name = concat_ws(' ', t.scientific_name, t.scientific_name_authorship) 
    or pn.canonical_name_complete = concat_ws(' ', t.scientific_name, t.scientific_name_authorship))
SQL;
        DB::connection($this->connection)->update($sql);

        // canonical name matches
        $sql = <<<SQL
update mapper.parsed_names pn 
set vicflora_scientific_name_id = t.scientific_name_id, name_match_type = 'canonicalNameMatch'
from mapper.taxa t
where pn.canonical_name_with_marker = t.scientific_name and pn.vicflora_scientific_name_id is null
SQL;
        DB::connection($this->connection)->update($sql);
    }
}
