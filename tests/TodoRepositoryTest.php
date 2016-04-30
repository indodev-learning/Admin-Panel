<?php

use App\Models\Todo;
use App\Repositories\TodoRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoRepositoryTest extends TestCase
{
    use MakeTodoTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var TodoRepository
     */
    protected $todoRepo;

    public function setUp()
    {
        parent::setUp();
        $this->todoRepo = App::make(TodoRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateTodo()
    {
        $todo = $this->fakeTodoData();
        $createdTodo = $this->todoRepo->create($todo);
        $createdTodo = $createdTodo->toArray();
        $this->assertArrayHasKey('id', $createdTodo);
        $this->assertNotNull($createdTodo['id'], 'Created Todo must have id specified');
        $this->assertNotNull(Todo::find($createdTodo['id']), 'Todo with given id must be in DB');
        $this->assertModelData($todo, $createdTodo);
    }

    /**
     * @test read
     */
    public function testReadTodo()
    {
        $todo = $this->makeTodo();
        $dbTodo = $this->todoRepo->find($todo->id);
        $dbTodo = $dbTodo->toArray();
        $this->assertModelData($todo->toArray(), $dbTodo);
    }

    /**
     * @test update
     */
    public function testUpdateTodo()
    {
        $todo = $this->makeTodo();
        $fakeTodo = $this->fakeTodoData();
        $updatedTodo = $this->todoRepo->update($fakeTodo, $todo->id);
        $this->assertModelData($fakeTodo, $updatedTodo->toArray());
        $dbTodo = $this->todoRepo->find($todo->id);
        $this->assertModelData($fakeTodo, $dbTodo->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteTodo()
    {
        $todo = $this->makeTodo();
        $resp = $this->todoRepo->delete($todo->id);
        $this->assertTrue($resp);
        $this->assertNull(Todo::find($todo->id), 'Todo should not exist in DB');
    }
}
