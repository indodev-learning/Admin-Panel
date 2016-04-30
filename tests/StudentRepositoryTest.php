<?php

use App\Models\Student;
use App\Repositories\StudentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StudentRepositoryTest extends TestCase
{
    use MakeStudentTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var StudentRepository
     */
    protected $studentRepo;

    public function setUp()
    {
        parent::setUp();
        $this->studentRepo = App::make(StudentRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateStudent()
    {
        $student = $this->fakeStudentData();
        $createdStudent = $this->studentRepo->create($student);
        $createdStudent = $createdStudent->toArray();
        $this->assertArrayHasKey('id', $createdStudent);
        $this->assertNotNull($createdStudent['id'], 'Created Student must have id specified');
        $this->assertNotNull(Student::find($createdStudent['id']), 'Student with given id must be in DB');
        $this->assertModelData($student, $createdStudent);
    }

    /**
     * @test read
     */
    public function testReadStudent()
    {
        $student = $this->makeStudent();
        $dbStudent = $this->studentRepo->find($student->id);
        $dbStudent = $dbStudent->toArray();
        $this->assertModelData($student->toArray(), $dbStudent);
    }

    /**
     * @test update
     */
    public function testUpdateStudent()
    {
        $student = $this->makeStudent();
        $fakeStudent = $this->fakeStudentData();
        $updatedStudent = $this->studentRepo->update($fakeStudent, $student->id);
        $this->assertModelData($fakeStudent, $updatedStudent->toArray());
        $dbStudent = $this->studentRepo->find($student->id);
        $this->assertModelData($fakeStudent, $dbStudent->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteStudent()
    {
        $student = $this->makeStudent();
        $resp = $this->studentRepo->delete($student->id);
        $this->assertTrue($resp);
        $this->assertNull(Student::find($student->id), 'Student should not exist in DB');
    }
}
