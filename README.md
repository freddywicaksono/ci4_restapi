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
This will create table: students

![image](https://github.com/freddywicaksono/ci4_restapi/blob/main/table_students.JPG)
## 6. Create Model for Student
```
php spark make:model Student
```
This will create a file in app/Models/Student.php

- Edit the file to set allow field for Entry:
```
protected $allowedFields    = [];
```
change to:
```
protected $allowedFields    = ['name', 'email'];
```
## 7. Create Controller for Student
```
php spark make:controller Students --restful
```
This will create a file in app/Controllers/Students.php
- Edit file Students.php
```php
<?php

namespace App\Controllers;

use App\Models\Student;
use CodeIgniter\RESTful\ResourceController;

class Students extends ResourceController
{

    private $student;

    public function __construct()
    {
        $this->student = new Student();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $students = $this->student->findAll();
        return $this->respond($students);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $student = $this->student->find($id);
        if ($student) {
            return $this->respond($student);
        }
        return $this->failNotFound('Sorry! no student found');
    }


    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $validation = $this->validate([
            'name' => 'required',
            "email" => "required|valid_email|is_unique[students.email]|min_length[6]",
        ]);

        if (!$validation) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $student = [
            'name' => $this->request->getVar('name'),
            'email' => $this->request->getVar('email')
        ];

        $studentId = $this->student->insert($student);
        if ($studentId) {
            $student['id'] = $studentId;
            return $this->respondCreated($student);
        }
        return $this->fail('Sorry! no student created');
    }


    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $student = $this->student->find($id);
        if ($student) {

            $validation = $this->validate([
                'name' => 'required',
                "email" => "required|valid_email",
            ]);

            if (!$validation) {
                return $this->failValidationErrors($this->validator->getErrors());
            }

            $student = [
                'id' => $id,
                'name' => $this->request->getVar('name'),
                'email' => $this->request->getVar('email')
            ];

            $response = $this->student->save($student);
            if ($response) {
                return $this->respond($student);
            }
            return $this->fail('Sorry! not updated');
        }
        return $this->failNotFound('Sorry! no student found');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $student = $this->student->find($id);
        if ($student) {
            $response = $this->student->delete($id);
            if ($response) {
                return $this->respond($student);
            }
            return $this->fail('Sorry! not deleted');
        }
        return $this->failNotFound('Sorry! no student found');
    }
}
```
## 8. Add Restful Route for Student
Edit file app/Config/Routes.php
```
$routes->get('/', 'Home::index');
```
Change to:
```
$routes->get('/', 'Home::index');
$routes->resource('students');
```
## 9. Edit file app/Config/App.php
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
## 10. Copy file .htaccess and index.php from public folder to root directory
Edit file index.php in root directory:
```
require FCPATH . '../app/Config/Paths.php';
```
change into :
```
require FCPATH . 'app/Config/Paths.php';
```
## Testing API in Postman
![image](https://github.com/freddywicaksono/ci4_restapi/blob/main/api_demo_entry.JPG)
