<?php

use Faker\Factory as Faker;
use App\Models\Todo;
use App\Repositories\TodoRepository;

trait MakeTodoTrait
{
    /**
     * Create fake instance of Todo and save it in database
     *
     * @param array $todoFields
     * @return Todo
     */
    public function makeTodo($todoFields = [])
    {
        /** @var TodoRepository $todoRepo */
        $todoRepo = App::make(TodoRepository::class);
        $theme = $this->fakeTodoData($todoFields);
        return $todoRepo->create($theme);
    }

    /**
     * Get fake instance of Todo
     *
     * @param array $todoFields
     * @return Todo
     */
    public function fakeTodo($todoFields = [])
    {
        return new Todo($this->fakeTodoData($todoFields));
    }

    /**
     * Get fake data of Todo
     *
     * @param array $postFields
     * @return array
     */
    public function fakeTodoData($todoFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'note' => $fake->text,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $todoFields);
    }
}
