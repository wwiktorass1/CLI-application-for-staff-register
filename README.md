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
* **├── src/**
* **│   ├── App/**
* **│   │   ├── CsvImporter.php**           Logic for importing CSV files
* **│   │   ├── PersonManager.php**       Logic for managing staff data
* **├── tests/**
* **│   ├── CsvImporterTest.php**           Unit tests for CSV importing
* **│   ├── PersonManagerTest.php**       Unit tests for staff management
* **├── vendor/**                                    Composer dependencies
* **├── commands.php**                             Main CLI entry point
* **├── composer.json**                        Composer configuration
* **├── staff.db**                                    SQLite database




## Installation
1. **Clone the repository:**
   ```bash
   git clone [https://github.com/wwiktorass1/CLI-application-for-staff-register.git](https://github.comwwiktorass1/CLI-application-for-staff-register.git)
   ```


##   Install dependencies:
```Bash
 cd your-repo
 composer install
```

 ##  Verify PHP version
Bash
php -v

## Prepare the SQLite database
* Create the staff.db file in the root directory if it doesn't already exist:
**touch staff.db**


## Usage
Run the application:
```Bash
 php commands.php
```

Menu Options
* Add a new employee: Allows you to input a new employee's details.
* Delete an employee by email: Removes an employee from the database using their email address.
* Search for an employee by email: Searches for an employee and displays their details.
* Import employees from CSV file: Imports employee data from a properly formatted CSV file.
* View all employees: Displays all employee data in the database.
* Exit: Closes the application.



Run tests:
Bash
## vendor/bin/phpunit tests

**Expected results**
* Tests should pass without any errors.
* If any test fails, verify the file formats and ensure all dependencies are installed.

# CSV Import Requirements 
**File Format**
* The CSV file must include a header row:

  ## Example CSV file
### firstname;lastname;email;phonenumber1;phonenumber2;comment
### Jonas;Jonaitis;jonas@example.com;+37061234567;;Programmer**
### Petras;Petraitis;petras@example.com;+37061234568;;Lawyer**

# Potential Improvements

**vEnhanced CSV Parsing**
* Automatically detect the delimiter (; or ,).
* Add support for UTF-8 files with BOM.
**Phone Number Validation**
* Implement phone number validation:
###  * Ensure numbers follow international formats (e.g., +37061234567). 
###  * Validate numbers against specific country patterns. 
###  * Provide detailed error messages, e.g., "Too short" or "Invalid prefix."
**API Development**
* Create a RESTful API for easier integration and remote access.
**Database Enhancements**
* Switch to MySQL or PostgreSQL for handling larger datasets.
**Authentication**
* Add user authentication for CLI or API usage.
**Additional Tests**
* Add tests for edge cases, such as empty files, special characters, or invalid data.
**Improved CLI Interface**
* Use interactive libraries like symfony/console to enhance user experience with features like colored messages and better input handling.


  # Author
Developed by Viktoras.
