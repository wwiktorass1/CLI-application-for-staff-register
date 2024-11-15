<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\PersonManager;
use PDO;

class PersonManagerTest extends TestCase
{
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

    private PDO $db;

    protected function setUp(): void
    {
        $this->db = new PDO('sqlite::memory:');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->manager = new PersonManager($this->db);
    
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
    
        $this->db->exec("DELETE FROM staff");
    }
    

    public function testAddPerson(): void
    {
        $person = [
            'firstname' => 'Jonas',
            'lastname' => 'Jonaitis',
            'email' => 'jonas@example.com',
            'phonenumber1' => '+37061234567',
            'phonenumber2' => '',
            'comment' => 'Test'
        ];

        $this->assertTrue($this->manager->addPerson($person));
    }

    public function testFindPersonByEmail(): void
    {
        $person = [
            'firstname' => 'Jonas',
            'lastname' => 'Jonaitis',
            'email' => 'jonas@example.com',
            'phonenumber1' => '+37061234567',
            'phonenumber2' => '',
            'comment' => 'Test'
        ];

        $this->manager->addPerson($person);
        $foundPerson = $this->manager->findPersonByEmail('jonas@example.com');

        $this->assertNotEmpty($foundPerson);
        $this->assertEquals('Jonas', $foundPerson['firstname']);
    }

    public function testDeletePerson(): void
    {
        $person = [
            'firstname' => 'Jonas',
            'lastname' => 'Jonaitis',
            'email' => 'jonas@example.com',
            'phonenumber1' => '+37061234567',
            'phonenumber2' => '',
            'comment' => 'Test'
        ];

        $this->manager->addPerson($person);
        $this->assertTrue($this->manager->deletePerson('jonas@example.com'));

        $foundPerson = $this->manager->findPersonByEmail('jonas@example.com');
        $this->assertEmpty($foundPerson);
    }

    public function testDuplicateEmail(): void
    {
        $person = [
            'firstname' => 'Jonas',
            'lastname' => 'Jonaitis',
            'email' => 'jonas@example.com',
            'phonenumber1' => '+37061234567',
            'phonenumber2' => '',
            'comment' => 'Test'
        ];

        $this->manager->addPerson($person);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Person with this email already exists.");
        $this->manager->addPerson($person);
    }

}
