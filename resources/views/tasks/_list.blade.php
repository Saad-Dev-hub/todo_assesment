@forelse ($tasks as $task)
    @include('tasks._task', ['task' => $task])
@empty
    <div class="text-center py-4 text-gray-500">
        No tasks found.
    </div>
@endforelse
