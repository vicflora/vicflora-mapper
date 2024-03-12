<?php

namespace App\Console\Commands;

use App\Actions\MatchName;
use App\Actions\ParseName;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MapperParseAndMatchNewNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:parse-and-match-new-names {--schema=mapper}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses and matches new names that come with the ALA data to the VicFlora taxonomy';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $parse = new ParseName;
        $match = new MatchName;

        $schema = $this->option('schema');

        $newNames = DB::table("{$schema}.occurrences")
                ->whereNull('parsed_name_id')
                ->whereRaw("trim(scientific_name) != ''")
                ->select('scientific_name')
                ->distinct()
                ->get();

        foreach ($newNames as $name) {
            $nameStr = $name->scientific_name;
            $parsedName = $parse($nameStr);

            $insert = [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'scientific_name' => $nameStr,
                'type' => $parsedName->type,
                'authors_parsed' => $parsedName->parsed,
                'genus_or_above' => isset($parsedName->genusOrAbove) ? $parsedName->genusOrAbove : null,
                'specific_epithet' => isset($parsedName->specificEpithet) ? $parsedName->specificEpithet : null,
                'infraspecific_epithet' => isset($parsedName->infrspecificEpithet) ? $parsedName->infraspecificEpithet : null,
                'notho' => isset($parsedName->notho) ? $parsedName->notho : null,
                'rank_marker' => isset($parsedName->rankMarker) ? $parsedName->rankMarker : null,
                'authorship' => isset($parsedName->authorship) ? $parsedName->authorship : null,
                'bracket_authorship' => isset($parsedName->bracketAuthorship) ? $parsedName->bracketAuthorship : null,
                'year' => isset($parsedName->year) ? $parsedName->year : null,
                'bracket_year' => isset($parsedName->bracketYear) ? $parsedName->bracketYear : null,
                'sensu' => isset($parsedName->sensu) ? $parsedName->sensu : null,
                'nom_status' => isset($parsedName->nomStatus) ? $parsedName->nomStatus : null,
                'canonical_name' => isset($parsedName->canonicalName) ? $parsedName->canonicalName : null,
                'canonical_name_with_marker' => isset($parsedName->canonicalNameWithMarker) ? $parsedName->canonicalNameWithMarker : null,
                'canonical_name_complete' => isset($parsedName->canonicalNameComplete) ? $parsedName->canonicalNameComplete : null,
            ];

            $matchedName = $match($parsedName, $schema);

            if ($matchedName) {
                $insert['vicflora_scientific_name_id'] = $matchedName->scientific_name_id;
                $insert['name_match_type'] = $matchedName->match_type;
            }

            DB::table("{$schema}.parsed_names")->upsert($insert,
                    ['scientific_name'],
                    ['vicflora_scientific_name_id', 'name_match_type']);
        }

        $update = <<<SQL
update $schema.occurrences
set parsed_name_id = parsed_names.id
from $schema.parsed_names
where occurrences.parsed_name_id is null
	and occurrences.scientific_name = parsed_names.scientific_name
SQL;
        DB::statement($update);

        return 0;
    }
}
