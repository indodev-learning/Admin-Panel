<?php

namespace App\Repositories;

use App\Models\Todo;
use InfyOm\Generator\Common\BaseRepository;

class TodoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'note'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Todo::class;
    }
}
