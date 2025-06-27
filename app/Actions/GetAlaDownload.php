<?php

namespace App\Actions;

use App\Actions\ExtractAllFilesFromZipArchive;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GetAlaDownload
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
    public function __invoke(string $statusUrl, string $filename, int $wait=30)
    {
        $this->checkStatus($statusUrl, $filename, $wait);
        return 0;
    }

    private function checkStatus($statusUrl, $filename, $wait)
    {
        $res = Http::get($statusUrl);
        $body = $res->getBody();

        $json = json_decode($body);

        if (isset($json->downloadUrl)) {
            $contents = file_get_contents($json->downloadUrl);
            Storage::put("ala/$filename.zip", $contents);
            $extract = new ExtractAllFilesFromZipArchive;
            $archive = storage_path('app/private/ala/' . $filename) . '.zip';
            $extractTo = storage_path('app/private/ala/' . $filename);
            $extract($archive, $extractTo);
        }
        else {
            sleep($wait);
            $this->checkStatus($statusUrl, $filename, $wait);
        }
    }
}
