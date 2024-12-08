<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->tasks()
            ->with('category')
            ->latest();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('completed', $request->status === 'completed');
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->paginate(10);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'tasks' => view('tasks._list', ['tasks' => $tasks])->render(),
                'pagination' => $tasks->links()->render()
            ]);
        }

        $categories = Category::all();
        return view('tasks.index', compact('tasks', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        try {
            $validated = $request->validated();
            $task = Auth::user()->tasks()->create($validated);
            $task->load('category');

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task created successfully',
                    'task' => view('tasks._task', compact('task'))->render()
                ]);
            }

            return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create task',
                    'errors' => ['general' => [$e->getMessage()]]
                ], 422);
            }

            return back()->withInput()->with('error', 'Failed to create task: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update($request->validated());
        $task->load('category'); // Eager load category for the response

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'task' => view('tasks._task', compact('task'))->render()
            ]);
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]);
        }

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    /**
     * Update the status of the specified resource in storage.
     */
    public function updateStatus(Task $task)
    {
        $this->authorize('update', $task);
        
        $task->update([
            'status' => $task->status === 'completed' ? 'pending' : 'completed'
        ]);
        
        $task->load('category'); // Eager load category for the response

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully',
                'task' => view('tasks._task', compact('task'))->render()
            ]);
        }

        return redirect()->route('tasks.index')->with('success', 'Task status updated successfully.');
    }
}
