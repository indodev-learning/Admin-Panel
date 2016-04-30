<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTodoAPIRequest;
use App\Http\Requests\API\UpdateTodoAPIRequest;
use App\Models\Todo;
use App\Repositories\TodoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class TodoController
 * @package App\Http\Controllers\API
 */

class TodoAPIController extends AppBaseController
{
    /** @var  TodoRepository */
    private $todoRepository;

    public function __construct(TodoRepository $todoRepo)
    {
        $this->todoRepository = $todoRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/todos",
     *      summary="Get a listing of the Todos.",
     *      tags={"Todo"},
     *      description="Get all Todos",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Todo")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $this->todoRepository->pushCriteria(new RequestCriteria($request));
        $this->todoRepository->pushCriteria(new LimitOffsetCriteria($request));
        $todos = $this->todoRepository->all();

        return $this->sendResponse($todos->toArray(), 'Todos retrieved successfully');
    }

    /**
     * @param CreateTodoAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/todos",
     *      summary="Store a newly created Todo in storage",
     *      tags={"Todo"},
     *      description="Store Todo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Todo that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Todo")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Todo"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateTodoAPIRequest $request)
    {
        $input = $request->all();

        $todos = $this->todoRepository->create($input);

        return $this->sendResponse($todos->toArray(), 'Todo saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/todos/{id}",
     *      summary="Display the specified Todo",
     *      tags={"Todo"},
     *      description="Get Todo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Todo",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Todo"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Todo $todo */
        $todo = $this->todoRepository->find($id);

        if (empty($todo)) {
            return Response::json(ResponseUtil::makeError('Todo not found'), 400);
        }

        return $this->sendResponse($todo->toArray(), 'Todo retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateTodoAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/todos/{id}",
     *      summary="Update the specified Todo in storage",
     *      tags={"Todo"},
     *      description="Update Todo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Todo",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Todo that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Todo")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Todo"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateTodoAPIRequest $request)
    {
        $input = $request->all();

        /** @var Todo $todo */
        $todo = $this->todoRepository->find($id);

        if (empty($todo)) {
            return Response::json(ResponseUtil::makeError('Todo not found'), 400);
        }

        $todo = $this->todoRepository->update($input, $id);

        return $this->sendResponse($todo->toArray(), 'Todo updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/todos/{id}",
     *      summary="Remove the specified Todo from storage",
     *      tags={"Todo"},
     *      description="Delete Todo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Todo",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Todo $todo */
        $todo = $this->todoRepository->find($id);

        if (empty($todo)) {
            return Response::json(ResponseUtil::makeError('Todo not found'), 400);
        }

        $todo->delete();

        return $this->sendResponse($id, 'Todo deleted successfully');
    }
}
