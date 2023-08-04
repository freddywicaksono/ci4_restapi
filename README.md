# Create Restful API using Codeigniter 4
Please follow these steps:
## 1. Download Codeigniter 4
- Extract, then copy folder to c:/xampp/htdocs
- Rename folder to 'demo'

## 2. Start XAMPP Control Panel
- Start Apache
- Start MySQL

## 3. Open PHPMyAdmin
```
http://localhost/phpmyadmin
```
- Create Database: 'demo'

## 4. Rename file env to .env
Edit file .env
```
# CI_ENVIRONMENT = production

# database.default.hostname = localhost
# database.default.database = ci4
# database.default.username = root
# database.default.password = root
# database.default.DBDriver = MySQLi
# database.default.DBPrefix =
# database.default.port = 3306
```
change to
```
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = demo
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```
## 5. Create table Students
```
php spark make:migration Student
```
This will generate a new file inside the app/Database/Migrations folder: timestamp_Student.php
- edit file 'timestamp_Student.php'
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Student extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('students');
    }

    public function down()
    {
        $this->forge->dropTable('students');
    }
}
```
```
php spark migrate
```
## 2. Edit file app/Config/App.php
```
public string $baseURL = 'http://localhost:8080/';
public string $indexPage = 'index.php';
public string $uriProtocol = 'REQUEST_URI';
```
change to:
```
public string $baseURL = 'http://localhost/demo/';
public string $indexPage = '';
public string $uriProtocol = 'PATH_INFO';

```
## 3. Copy file .htaccess and index.php from public folder to root directory
Edit file index.php in root directory:
```
require FCPATH . '../app/Config/Paths.php';
```
change into :
```
require FCPATH . 'app/Config/Paths.php';
```
