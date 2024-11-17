<?php

namespace App;

use PDO;
use Exception;

class CsvImporter
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

        public function importCsv(string $filename, string $separator = ';'): array
    {
        if (!file_exists($filename)) {
            throw new Exception("File not found");
        }

        $this->db->exec("DELETE FROM staff");

        $handle = fopen($filename, 'r');
        $success = 0;
        $failed = 0;
        $failedRecords = [];

        fgetcsv($handle, 1000, $separator);

        while (($data = fgetcsv($handle, 1000, $separator)) !== false) {
            if (empty($data) || count(array_filter($data)) === 0) {
                continue;
            }

            if (count($data) < 6 || empty($data[0]) || empty($data[1]) || empty($data[2])) {
                $failed++;
                $failedRecords[] = [
                    'row' => $data,
                    'reason' => 'Missing required fields'
                ];
                continue;
            }

            if (!filter_var($data[2], FILTER_VALIDATE_EMAIL)) {
                $failed++;
                $failedRecords[] = [
                    'row' => $data,
                    'reason' => 'Invalid email format'
                ];
                continue;
            }

            $stmtCheck = $this->db->prepare("SELECT COUNT(*) FROM staff WHERE email = ?");
            $stmtCheck->execute([$data[2]]);
            $exists = $stmtCheck->fetchColumn();

            if ($exists) {
                $failed++;
                $failedRecords[] = [
                    'row' => $data,
                    'reason' => 'Duplicate email'
                ];
                continue;
            }
           
            try {
                $stmt = $this->db->prepare("
                    INSERT INTO staff (firstname, lastname, email, phonenumber1, phonenumber2, comment)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $data[0] ?? '',
                    $data[1] ?? '',
                    $data[2] ?? '',
                    $data[3] ?? '',
                    $data[4] ?? '',
                    $data[5] ?? '',
                ]);
                $success++;
            } catch (Exception $e) {
                $failed++;
                $failedRecords[] = [
                    'row' => $data,
                    'reason' => $e->getMessage()
                ];
            }
        }

        fclose($handle);

        return ['success' => $success, 'failed' => $failed, 'failedRecords' => $failedRecords];
    }

}
