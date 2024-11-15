<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\CsvImporter;
use PDO;

class CsvImporterTest extends TestCase
{
    private CsvImporter $importer;
    private PDO $db;

    protected function setUp(): void
    {
        $this->db = new PDO('sqlite::memory:');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->importer = new CsvImporter($this->db);

        $this->db->exec("
            CREATE TABLE staff (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                firstname TEXT NOT NULL,
                lastname TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                phonenumber1 TEXT,
                phonenumber2 TEXT,
                comment TEXT
            )
        ");
    }

    public function testImportCsv(): void
    {
        $csvContent = "firstname,lastname,email,phonenumber1,phonenumber2,comment\n";
        $csvContent .= "Jonas,Jonaitis,jonas@example.com,+37061234567,,Test\n";
        $csvContent .= "Petras,Petraitis,petras@example.com,+37061234568,,Test\n";

        $tempFile = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tempFile, $csvContent);

        $result = $this->importer->importCsv($tempFile);

        $this->assertEquals(2, $result['success']);
        $this->assertEquals(0, $result['failed']);

        unlink($tempFile);
    }

    public function testImportCsvWithDuplicates(): void
    {
        $csvContent = "firstname,lastname,email,phonenumber1,phonenumber2,comment\n";
        $csvContent .= "Jonas,Jonaitis,jonas@example.com,+37061234567,,Test\n";
        $csvContent .= "Jonas,Jonaitis,jonas@example.com,+37061234567,,Test\n";

        $tempFile = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tempFile, $csvContent);

        $result = $this->importer->importCsv($tempFile);

        $this->assertEquals(1, $result['success']);
        $this->assertEquals(1, $result['failed']);

        unlink($tempFile);
    }
    

}
