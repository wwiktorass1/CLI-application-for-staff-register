<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\PersonManager;
use App\CsvImporter;

$db = new PDO('sqlite:' . __DIR__ . '/staff.db');
$personManager = new PersonManager($db);
$csvImporter = new CsvImporter($db);

function showMenu()
{
    echo "=== Staff Register CLI ===\n";
    echo "1. Register a new person\n";
    echo "2. Delete a person\n";
    echo "3. Find a person\n";
    echo "4. Import from CSV\n";
    echo "5. List all persons\n";
    echo "6. Exit\n";
    echo "Choose an option: ";
}

while (true) {
    showMenu();
    $choice = trim(fgets(STDIN));

    switch ($choice) {
        case '1':
            echo "Enter first name: ";
            $firstname = trim(fgets(STDIN));
            echo "Enter last name: ";
            $lastname = trim(fgets(STDIN));
            echo "Enter email: ";
            $email = trim(fgets(STDIN));
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email format. Please try again." . PHP_EOL;
                continue 2;
            }
            echo "Enter phone number 1: ";
            $phonenumber1 = trim(fgets(STDIN));
            echo "Enter phone number 2 (optional): ";
            $phonenumber2 = trim(fgets(STDIN));
            echo "Enter comment: ";
            $comment = trim(fgets(STDIN));

            $person = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'phonenumber1' => $phonenumber1,
                'phonenumber2' => $phonenumber2,
                'comment' => $comment,
            ];

            try {
                $personManager->addPerson($person);
                echo "Person added successfully.\n";
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . "\n";
            }
            break;

        case '2':
            echo "Enter email of the person to delete: ";
            $email = trim(fgets(STDIN));
            if ($personManager->deletePerson($email)) {
                echo "Person deleted successfully.\n";
            } else {
                echo "Person not found.\n";
            }
            break;

        case '3':
            echo "Enter email of the person to find: ";
            $email = trim(fgets(STDIN));
            $person = $personManager->findPersonByEmail($email);
            if ($person) {
                print_r($person);
            } else {
                echo "Person not found.\n";
            }
            break;

        case '4':
            echo "Enter the path to the CSV file: ";
            $filePath = trim(fgets(STDIN));
            if (!file_exists($filePath)) {
                echo "Error: File does not exist.\n";
                continue 2;
            }

            $result = $csvImporter->importCsv($filePath);
            echo "Imported successfully: {$result['success']} persons." . PHP_EOL;
            if ($result['failed'] > 0) {
                echo "Failed to import: {$result['failed']} records." . PHP_EOL;
                foreach ($result['failedRecords'] as $record) {
                    echo "Row: [" . implode(", ", $record['row']) . "] - Reason: {$record['reason']}\n";
                }
            }
            break;

        case '5':
            $persons = $personManager->listAll(); 
            foreach ($persons as $person) {
                echo implode(", ", $person) . "\n";
            }
            break;

        case '6':
            echo "Goodbye!\n";
            exit(0);

        default:
            echo "Invalid choice. Please try again.\n";
            break;
    }
}
