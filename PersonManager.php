<?php

namespace App;

use PDO;
use InvalidArgumentException;

class PersonManager
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->initDatabase();
    }

    private function initDatabase(): void
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS staff (
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

    public function addPerson(array $data): bool
    {
        $existingPerson = $this->findPersonByEmail($data['email']);
        if (!empty($existingPerson)) {
            throw new InvalidArgumentException("Person with this email already exists.");
        }

        $stmt = $this->db->prepare("
            INSERT INTO staff (firstname, lastname, email, phonenumber1, phonenumber2, comment)
            VALUES (:firstname, :lastname, :email, :phonenumber1, :phonenumber2, :comment)
        ");

        return $stmt->execute($data);
    }

    public function deletePerson(string $email): bool
    {
        $stmt = $this->db->prepare("DELETE FROM staff WHERE email = :email");
        return $stmt->execute(['email' => $email]);
    }

    public function findPersonByEmail(string $email): array
    {
        $stmt = $this->db->prepare("SELECT * FROM staff WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function listAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM staff");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
