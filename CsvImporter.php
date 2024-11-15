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

    public function importCsv(string $filename): array
    {
        if (!file_exists($filename)) {
            throw new Exception("File not found");
        }
    
        $this->db->exec("DELETE FROM staff");
    
        $handle = fopen($filename, 'r');
        $success = 0;
        $failed = 0;
    
        fgetcsv($handle); // Praleidžiame antraštės eilutę
    
        while (($data = fgetcsv($handle)) !== false) {
            $stmtCheck = $this->db->prepare("SELECT COUNT(*) FROM staff WHERE email = ?");
            $stmtCheck->execute([$data[2]]);
            $exists = $stmtCheck->fetchColumn();
    
            if ($exists) {
                $failed++;
                continue;
            }
    
            try {
                $stmt = $this->db->prepare("
                    INSERT INTO staff (firstname, lastname, email, phonenumber1, phonenumber2, comment)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute($data);
                $success++;
            } catch (Exception $e) {
                $failed++;
            }
        }
    
        fclose($handle);
        return ['success' => $success, 'failed' => $failed];
    }
}    
    
