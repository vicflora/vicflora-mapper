<?php

namespace App\Actions;

use ZipArchive;

class ExtractAllFilesFromZipArchive
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
    public function __invoke(string $archive, string $extractTo): void
    {
        $zip = new ZipArchive();
        if ($zip->open($archive) === true) {
            if (!is_dir($extractTo)) {
                mkdir($extractTo);
            }
            $zip->extractTo($extractTo);
            $zip->close();
        }
    }
}
