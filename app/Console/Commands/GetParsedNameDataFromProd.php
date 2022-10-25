<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class GetParsedNameDataFromProd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:get-parsed-name-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets parsed name data from production database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $names = DB::connection('prod')->table('mapper.parsed_names')
                ->select(
                    'id',
                    'created_at',
                    'updated_at',
                    'scientific_name',
                    'type',
                    'authors_parsed',
                    'genus_or_above',
                    'infrageneric',
                    'specific_epithet',
                    'infraspecific_epithet',
                    'cultivar_epithet',
                    'strain',
                    'notho',
                    'rank_marker',
                    'authorship',
                    'bracket_authorship',
                    'year',
                    'bracket_year',
                    'sensu',
                    'nom_status',
                    'canonical_name',
                    'canonical_name_with_marker',
                    'canonical_name_complete',
                    'vicflora_scientific_name_id',
                    'name_match_type'
                )->get();

        foreach ($names as $name) {
            try {
                    DB::table('parsed_names')->insert([
                        'id' => $name->id,
                        'created_at' => $name->created_at,
                        'updated_at' => $name->updated_at,
                        'scientific_name' => $name->scientific_name,
                        'type' => $name->type,
                        'authors_parsed' => $name->authors_parsed,
                        'genus_or_above' => $name->genus_or_above,
                        'infrageneric' => $name->infrageneric,
                        'specific_epithet' => $name->specific_epithet,
                        'infraspecific_epithet' => $name->infraspecific_epithet,
                        'cultivar_epithet' => $name->cultivar_epithet,
                        'strain' => $name->strain,
                        'notho' => $name->notho,
                        'rank_marker' => $name->rank_marker,
                        'authorship' => $name->authorship,
                        'bracket_authorship' => $name->bracket_authorship,
                        'year' => $name->year,
                        'bracket_year' => $name->bracket_year,
                        'sensu' => $name->sensu,
                        'nom_status' => $name->nom_status,
                        'canonical_name' => $name->canonical_name,
                        'canonical_name_with_marker' => $name->canonical_name_with_marker,
                        'canonical_name_complete' => $name->canonical_name_complete,
                        'vicflora_scientific_name_id' => $name->vicflora_scientific_name_id,
                        'name_match_type' => $name->name_match_type            
                    ]);
                }
                catch(QueryException $exception) {
                    $this->error($exception->getMessage());
                }
        }

        return Command::SUCCESS;
    }
}
