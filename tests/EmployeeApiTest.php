<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeApiTest extends TestCase
{
    use MakeEmployeeTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateEmployee()
    {
        $employee = $this->fakeEmployeeData();
        $this->json('POST', '/api/v1/employees', $employee);

        $this->assertApiResponse($employee);
    }

    /**
     * @test
     */
    public function testReadEmployee()
    {
        $employee = $this->makeEmployee();
        $this->json('GET', '/api/v1/employees/'.$employee->id);

        $this->assertApiResponse($employee->toArray());
    }

    /**
     * @test
     */
    public function testUpdateEmployee()
    {
        $employee = $this->makeEmployee();
        $editedEmployee = $this->fakeEmployeeData();

        $this->json('PUT', '/api/v1/employees/'.$employee->id, $editedEmployee);

        $this->assertApiResponse($editedEmployee);
    }

    /**
     * @test
     */
    public function testDeleteEmployee()
    {
        $employee = $this->makeEmployee();
        $this->json('DELETE', '/api/v1/employees/'.$employee->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/employees/'.$employee->id);

        $this->assertResponseStatus(404);
    }
}
