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

        // Sukuriame lentelę duomenų bazėje
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

        $this->importer = new CsvImporter($this->db);
    }

    public function testValidCsvImport(): void
    {
        $csvContent = "firstname;lastname;email;phonenumber1;phonenumber2;comment\n";
        $csvContent .= "Jonas;Jonaitis;jonas@example.com;+37061234567;;Programuotojas\n";
        $csvContent .= "Petras;Petraitis;petras@example.com;+37061234568;;Analitikas\n";

        $filePath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($filePath, $csvContent);

        $result = $this->importer->importCsv($filePath, ';');

        $this->assertEquals(2, $result['success']);
        $this->assertEquals(0, $result['failed']);

        unlink($filePath);
    }

    public function testCsvWithInvalidEmail(): void
    {
        $csvContent = "firstname;lastname;email;phonenumber1;phonenumber2;comment\n";
        $csvContent .= "Jonas;Jonaitis;invalid-email;+37061234567;;Programuotojas\n";

        $filePath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($filePath, $csvContent);

        $result = $this->importer->importCsv($filePath, ';');

        $this->assertEquals(0, $result['success']);
        $this->assertEquals(1, $result['failed']);
        $this->assertEquals('Invalid email format', $result['failedRecords'][0]['reason']);

        unlink($filePath);
    }

    public function testCsvWithDuplicateEmail(): void
    {
        $csvContent = "firstname;lastname;email;phonenumber1;phonenumber2;comment\n";
        $csvContent .= "Jonas;Jonaitis;duplicate@example.com;+37061234567;;Programuotojas\n";
        $csvContent .= "Petras;Petraitis;duplicate@example.com;+37061234568;;Analitikas\n";

        $filePath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($filePath, $csvContent);

        $result = $this->importer->importCsv($filePath, ';');

        $this->assertEquals(1, $result['success']);
        $this->assertEquals(1, $result['failed']);
        $this->assertEquals('Duplicate email', $result['failedRecords'][0]['reason']);

        unlink($filePath);
    }

    public function testCsvWithMissingFields(): void
    {
        $csvContent = "firstname;lastname;email;phonenumber1;phonenumber2;comment\n";
        $csvContent .= "Jonas;Jonaitis;;+37061234567;;Programuotojas\n";

        $filePath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($filePath, $csvContent);

        $result = $this->importer->importCsv($filePath, ';');

        $this->assertEquals(0, $result['success']);
        $this->assertEquals(1, $result['failed']);
        $this->assertEquals('Missing required fields', $result['failedRecords'][0]['reason']);

        unlink($filePath);
    }

    public function testEmptyCsvFile(): void
    {
        $csvContent = "";

        $filePath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($filePath, $csvContent);

        $result = $this->importer->importCsv($filePath, ';');

        $this->assertEquals(0, $result['success']);
        $this->assertEquals(0, $result['failed']);

        unlink($filePath);
    }
}
