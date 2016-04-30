<?php

use Faker\Factory as Faker;
use App\Models\Employee;
use App\Repositories\EmployeeRepository;

trait MakeEmployeeTrait
{
    /**
     * Create fake instance of Employee and save it in database
     *
     * @param array $employeeFields
     * @return Employee
     */
    public function makeEmployee($employeeFields = [])
    {
        /** @var EmployeeRepository $employeeRepo */
        $employeeRepo = App::make(EmployeeRepository::class);
        $theme = $this->fakeEmployeeData($employeeFields);
        return $employeeRepo->create($theme);
    }

    /**
     * Get fake instance of Employee
     *
     * @param array $employeeFields
     * @return Employee
     */
    public function fakeEmployee($employeeFields = [])
    {
        return new Employee($this->fakeEmployeeData($employeeFields));
    }

    /**
     * Get fake data of Employee
     *
     * @param array $postFields
     * @return array
     */
    public function fakeEmployeeData($employeeFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'email' => $fake->word,
            'gaji' => $fake->word,
            'address' => $fake->text,
            'phone' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $employeeFields);
    }
}
