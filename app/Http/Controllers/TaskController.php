<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/tasks",
    *     summary="Display a listing of the tasks for the authenticated user.",
    *     tags={"Task"},
    *     security={{"sanctum":{}}},
    *     @OA\Response(
    *         response=200,
    *         description="List of Task"
    *     )
    * )
    */
    public function index()
    {
        $tasks = Auth::user()->tasks;

        return response()->json($tasks);
    }

    /**
    * @OA\Post(
    *     path="/api/tasks/create",
    *     summary="Create a new task",
    *     tags={"Task"},
    *     security={{"sanctum":{}}},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"title", "status", "due_date"},
    *             @OA\Property(property="title", type="string", example="Finish Laravel Project"),
    *             @OA\Property(property="description", type="string", example="Complete the task manager API project."),
    *             @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed"}, example="pending"),
    *             @OA\Property(property="due_date", type="string", format="date", example="2025-04-18")
    *         )
    *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Task was successfully created"
    *     ),
    *     @OA\Response(
    *         response=422,
    *         description="Validation error"
    *     )
    * )
    */
    public function store(StoreTaskRequest $request)
    {
        // Validate incoming data
        $validated = $request->validated();

        // Create task and associate with authenticated user
        $task = Auth::user()->tasks()->create($validated);

        return response()->json($task, 201);
    }

    /**
    * @OA\Put(
    *     path="/api/tasks/{id}",
    *     summary="Update an existing task",
    *     tags={"Task"},
    *     security={{"sanctum":{}}},
    *    @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="ID of the task to update",
    *         @OA\Schema(type="integer", example=1)
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             @OA\Property(property="title", type="string", example="Update Task"),
    *             @OA\Property(property="description", type="string", example="This task was already completed"),
    *             @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed"}, example="completed"),
    *         )
    *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Task was successfully updated"
    *     ),
    *     @OA\Response(
    *         response=422,
    *         description="Validation error"
    *     ),
    * )
    */
    public function update(UpdateTaskRequest $request, $id)
    {
        // Find the task or fail if not found
        $task = Auth::user()->tasks()->findOrFail($id);

        // Validate incoming data
        $validated = $request->validated();

        // Update the task
        $task->update($validated);

        return response()->json($task, 201);
    }

    /**
    * @OA\Delete(
    *     path="/api/tasks/{id}",
    *     summary="Delete a task",
    *     tags={"Task"},
    *     security={{"sanctum":{}}},
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="ID of the task to delete",
    *         @OA\Schema(type="integer", example=1)
    *     ),
    *     @OA\Response(
    *         response=204,
    *         description="Task was successfully deleted"
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Task not found"
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated"
    *     )
    * )
    */
    public function destroy($id)
    {
        // Find the task or fail if not found
        $task = Auth::user()->tasks()->findOrFail($id);

        // Delete the task
        $task->delete();

        return response()->json(null, 204);
    }

    /**
    * @OA\Get(
    *     path="/api/tasks/{id}",
    *     summary="Show a particular task",
    *     tags={"Task"},
    *     security={{"sanctum":{}}},
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="ID of the task to be selected",
    *         @OA\Schema(type="integer", example=1)
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Selected task"
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Task not found"
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated"
    *     )
    * )
    */
    public function show($id)
    {
        // Find the task or fail if not found
        $task = Auth::user()->tasks()->findOrFail($id);

        return response()->json($task, 201);
    }
}
