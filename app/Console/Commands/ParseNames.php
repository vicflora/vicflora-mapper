<?php

namespace App\Console\Commands;

use App\Actions\CreateParsedNamesTable;
use App\Actions\QueryGbifNameParser;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ParseNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse-names {--database=}';

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
        $parser = new QueryGbifNameParser;

        $sql = <<<SQL
create table mapper.raw_names as
select distinct scientific_name
from mapper.occurrences
where parsed_name_id is null
SQL;
        DB::connection($this->option('database'))->statement($sql);

        Schema::connection($this->option('database'))->table('mapper.raw_names', function (Blueprint $table) {
            $table->index('scientific_name');
        });

        (new CreateParsedNamesTable)(connection: $this->option('database'));

        DB::connection($this->option('database'))->table('mapper.raw_names')
            ->select('scientific_name')
            ->orderBy('scientific_name')
            ->chunk(1000, function (Collection $names) use ($parser) {
                $names = $names->map(fn ($name) => $name->scientific_name);

                if ($names->count()) {
                    $result = $parser->parse($names->toArray());
                    $insertData = collect($result)->map(function ($row) {
                        $keys = [
                            'key',
                            'scientificName',
                            'type',
                            'genusOrAbove',
                            'infraGeneric',
                            'specificEpithet',
                            'infraSpecificEpithet',
                            'cultivarEpithet',
                            'strain',
                            'notho',
                            'authorship',
                            'year',
                            'bracketAuthorship',
                            'bracketYear',
                            'sensu',
                            'parsed',
                            'parsedPartially',
                            'nomStatus',
                            'remarks',
                            'canonicalName',
                            'canonicalNameWithMarker',
                            'canonicalNameComplete',
                            'rankMarker'
                        ];

                        $ret = [];
                        foreach ($keys as $key) {
                            $ret[Str::replace('infra_', 'infra', Str::snake($key))] = isset($row[$key]) ? $row[$key] : null;
                        }
                        return $ret;
                    });

                    DB::connection($this->option('database'))->table('mapper.parsed_names')->insert($insertData->toArray());
                }
            });

        $sql = <<<SQL
update mapper.occurrences o
set parsed_name_id = pn.id
from mapper.parsed_names pn 
where o.parsed_name_id is null and o.scientific_name = pn.scientific_name
SQL;
        DB::connection($this->option('database'))->update($sql);

        Schema::connection($this->option('database'))->dropIfExists('mapper.raw_names');
    }
}
