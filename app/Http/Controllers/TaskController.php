<?php

namespace App\Http\Controllers;

use App\Http\Requests\JSONAPIRequest;
use App\Models\Task;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TaskController extends Controller
{

    /**
     * @var JSONAPIService
     */
    private $service;

    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->service->fetchResources(Task::class, 'tasks');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JSONAPIRequest $request)
    {
        return $this->service->createResource(Task::class, [
            'name' => $request->input('data.attributes.name'),
            'email' => $request->input('data.attributes.email'),
            'password' => Hash::make(($request->input('data.attributes.password'))),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $task
     * @return \Illuminate\Http\Response
     */
    public function show($task)
    {
        return $this->service->fetchResource(Task::class, $task, 'tasks');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JSONAPIRequest $request, Task $task)
    {
        $attributes = $request->input('data.attributes');

        if (!empty($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        return $this->service->updateResource($task, $attributes);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        return $this->service->deleteResource($task);
    }

}
