<?php

namespace App\Console\Commands;

use App\Actions\MatchName;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MapperRematchParsedNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapper:rematch-parsed-names';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $match = new MatchName;

        $names = DB::table('mapper.parsed_names')
            ->select(
                'id',
                'scientific_name as scientificName',
                'canonical_name_with_marker as canonicalNameWithMarker'
            )
            ->get();

        foreach ($names as $name) {
            $matchedName = $match($name);

            if ($matchedName) {
                DB::table('mapper.parsed_names')->where('id', $name->id)
                    ->update([
                        'vicflora_scientific_name_id' => $matchedName->scientific_name_id,
                        'name_match_type' => $matchedName->match_type,
                    ]);
            } else {
                DB::table('mapper.parsed_names')->where('id', $name->id)
                    ->update([
                        'vicflora_scientific_name_id' => null,
                        'name_match_type' => null,
                    ]);
            }
        }


        return Command::SUCCESS;
    }
}
