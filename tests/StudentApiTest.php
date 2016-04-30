<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StudentApiTest extends TestCase
{
    use MakeStudentTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateStudent()
    {
        $student = $this->fakeStudentData();
        $this->json('POST', '/api/v1/students', $student);

        $this->assertApiResponse($student);
    }

    /**
     * @test
     */
    public function testReadStudent()
    {
        $student = $this->makeStudent();
        $this->json('GET', '/api/v1/students/'.$student->id);

        $this->assertApiResponse($student->toArray());
    }

    /**
     * @test
     */
    public function testUpdateStudent()
    {
        $student = $this->makeStudent();
        $editedStudent = $this->fakeStudentData();

        $this->json('PUT', '/api/v1/students/'.$student->id, $editedStudent);

        $this->assertApiResponse($editedStudent);
    }

    /**
     * @test
     */
    public function testDeleteStudent()
    {
        $student = $this->makeStudent();
        $this->json('DELETE', '/api/v1/students/'.$student->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/students/'.$student->id);

        $this->assertResponseStatus(404);
    }
}
