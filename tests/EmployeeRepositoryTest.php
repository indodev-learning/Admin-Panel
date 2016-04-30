<?php

use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeRepositoryTest extends TestCase
{
    use MakeEmployeeTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var EmployeeRepository
     */
    protected $employeeRepo;

    public function setUp()
    {
        parent::setUp();
        $this->employeeRepo = App::make(EmployeeRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateEmployee()
    {
        $employee = $this->fakeEmployeeData();
        $createdEmployee = $this->employeeRepo->create($employee);
        $createdEmployee = $createdEmployee->toArray();
        $this->assertArrayHasKey('id', $createdEmployee);
        $this->assertNotNull($createdEmployee['id'], 'Created Employee must have id specified');
        $this->assertNotNull(Employee::find($createdEmployee['id']), 'Employee with given id must be in DB');
        $this->assertModelData($employee, $createdEmployee);
    }

    /**
     * @test read
     */
    public function testReadEmployee()
    {
        $employee = $this->makeEmployee();
        $dbEmployee = $this->employeeRepo->find($employee->id);
        $dbEmployee = $dbEmployee->toArray();
        $this->assertModelData($employee->toArray(), $dbEmployee);
    }

    /**
     * @test update
     */
    public function testUpdateEmployee()
    {
        $employee = $this->makeEmployee();
        $fakeEmployee = $this->fakeEmployeeData();
        $updatedEmployee = $this->employeeRepo->update($fakeEmployee, $employee->id);
        $this->assertModelData($fakeEmployee, $updatedEmployee->toArray());
        $dbEmployee = $this->employeeRepo->find($employee->id);
        $this->assertModelData($fakeEmployee, $dbEmployee->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteEmployee()
    {
        $employee = $this->makeEmployee();
        $resp = $this->employeeRepo->delete($employee->id);
        $this->assertTrue($resp);
        $this->assertNull(Employee::find($employee->id), 'Employee should not exist in DB');
    }
}
