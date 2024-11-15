# CLI-application-for-staff-register

**A simple PHP application for managing employee data.**

## Project Structure

## Project Structure
## ├── src/
## │   ├── App/
## │   │   ├── PersonManager.php
## │   │   ├── CsvImporter.php
## ├── commands.php
## ├── composer.json
## ├── vendor/

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
## cd your-repo
## composer install


## Usage
Run the application:
Bash
## php commands.php

Run tests:
Bash
## vendor/bin/phpunit tests
