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
protected $allowedFields    = ['name', 'email', 'created_at', 'updated_at']
```
Complete code:
```php
<?php
namespace App\Models;

use CodeIgniter\Model;

class StudentsModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'created_at', 'updated_at'];
}
```
## 7. Create Controller for Student
```
php spark make:controller Students --restful
```
This will create a file in app/Controllers/Students.php
- Edit file Students.php
```php
<?php
// File: app/Controllers/StudentsController.php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use App\Models\Student; // Make sure to import the StudentsModel

class Students extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\Student';
    protected $format    = 'json';

    public function index()
    {
        // Fetch all students from the database
        $model = new Student();
        $students = $model->findAll();

        return $this->respond($students);
    }

    public function show($id = null)
    {
        // Fetch a specific student by ID from the database
        $model = new Student();
        $student = $model->find($id);

        if (!$student) {
            return $this->failNotFound('Student not found.');
        }

        return $this->respond($student);
    }

    public function create()
    {
        // Handle the creation of a new student
        $model = new Student();

        $data = $this->request->getPost();
        $result = $model->insert($data);

        if (!$result) {
            return $this->fail($model->errors());
        }

        return $this->respondCreated($data, 'Student created.');
    }

    public function update($id = null)
    {
        // Handle updating an existing student
        $model = new Student();

        $data = $this->request->getRawInput();
        $data['id'] = $id;

        if ($model->update($id, $data) === false) {
            return $this->fail($model->errors());
        }

        return $this->respondUpdated($data, 'Student updated.');
    }

    public function delete($id = null)
    {
        // Handle deleting a student
        $model = new Student();

        $deleted = $model->delete($id);

        if ($deleted === false) {
            return $this->fail($model->errors());
        }

        return $this->respondDeleted(['id' => $id], 'Student deleted.');
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
$routes->get('students', 'Students::index');
$routes->get('students/(:num)', 'Students::show/$1');
$routes->post('students', 'Students::create');
$routes->put('students/(:num)', 'Students::update/$1');
$routes->delete('students/(:num)', 'Students::delete/$1');
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
Screenshot Tampil Semua Data:

![image](https://github.com/freddywicaksono/ci4_restapi/blob/main/api_demo_tampilsemua.JPG)

Screenshot Tampil Pencarian Data:

![image](https://github.com/freddywicaksono/ci4_restapi/blob/main/api_demo_tampilsatu.JPG)

Screenshot Entry Data:

![image](https://github.com/freddywicaksono/ci4_restapi/blob/main/api_demo_entry.JPG)

Screenshot Update Data:

![image](https://github.com/freddywicaksono/ci4_restapi/blob/main/api_demo_update.JPG)

Screenshot Delete Data:

![image](https://github.com/freddywicaksono/ci4_restapi/blob/main/api_demo_delete.JPG)
