<?php

namespace App\Actions;

class LoadDownloadedData
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
    public function __invoke(string $filename, array $columns, string $connection, string $table): void
    {
        $columns = implode(',', $columns);

        $host = config("database.connections.$connection.host");
        $username = config("database.connections.$connection.username");
        $passwd = config("database.connections.$connection.password");
        $database = config("database.connections.$connection.database");
        
        $command = <<<EOT
export PGPASSWORD=$passwd && psql -U $username -h $host -d $database -c "\copy ala.$table ($columns) FROM '$filename' CSV HEADER"
EOT;
        exec($command, $output);
    }
}
