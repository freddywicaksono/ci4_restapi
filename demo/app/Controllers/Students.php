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
