<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoApiTest extends TestCase
{
    use MakeTodoTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateTodo()
    {
        $todo = $this->fakeTodoData();
        $this->json('POST', '/api/v1/todos', $todo);

        $this->assertApiResponse($todo);
    }

    /**
     * @test
     */
    public function testReadTodo()
    {
        $todo = $this->makeTodo();
        $this->json('GET', '/api/v1/todos/'.$todo->id);

        $this->assertApiResponse($todo->toArray());
    }

    /**
     * @test
     */
    public function testUpdateTodo()
    {
        $todo = $this->makeTodo();
        $editedTodo = $this->fakeTodoData();

        $this->json('PUT', '/api/v1/todos/'.$todo->id, $editedTodo);

        $this->assertApiResponse($editedTodo);
    }

    /**
     * @test
     */
    public function testDeleteTodo()
    {
        $todo = $this->makeTodo();
        $this->json('DELETE', '/api/v1/todos/'.$todo->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/todos/'.$todo->id);

        $this->assertResponseStatus(404);
    }
}
