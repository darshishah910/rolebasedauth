<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    public function getAll()
    {
        return Task::with('user')->latest()->get();
    }

    public function create($data, $userId)
    {
        return Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'user_id' => $userId
        ]);
    }

    public function update($id, $data)
    {
        $task = Task::findOrFail($id);

        $task->update($data);

        return $task;
    }

    public function delete($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
    }
}