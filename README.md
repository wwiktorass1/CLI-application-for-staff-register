# CLI-application-for-staff-register

A simple command-line-based staff management system for handling employee data. This application allows you to:

* Add new employees.
* Delete employees.
* Search for employees by email.
* Import employee data from CSV files.
* View a list of all employees.
Data is stored in an SQLite database.


# Requirements
* PHP 7.4 or later.
* Composer.
* SQLite.


# Project Structure:
staff-register/
├── src/
│   ├── App/
│   │   ├── CsvImporter.php        # Logic for importing CSV files
│   │   ├── PersonManager.php      # Logic for managing staff data
├── tests/
│   ├── CsvImporterTest.php        # Unit tests for CSV importing
│   ├── PersonManagerTest.php      # Unit tests for staff management
├── vendor/                        # Composer dependencies
├── commands.php                   # Main CLI entry point
├── composer.json                  # Composer configuration
├── staff.db                       # SQLite database


* **src:** Contains the core application logic.
  * **App:** Namespace for application classes.
    * **PersonManager.php:** Handles employee data management.
    * **CsvImporter.php:** Imports employee data from CSV files.
* **commands.php:** Entry point for running the application.
* **composer.json:** Manages project dependencies.
* **vendor:** Contains third-party libraries.

## Installation
1. **Clone the repository:**
   ```bash
   git clone [https://github.com/wwiktorass1/CLI-application-for-staff-register.git](https://github.comwwiktorass1/CLI-application-for-staff-register.git)


##   Install dependencies:
Bash
 cd your-repo
 composer install


## Usage
Run the application:
Bash
## php commands.php

Run tests:
Bash
## vendor/bin/phpunit tests
