<div class="task-item border rounded-lg p-4 bg-white shadow-sm" data-task-id="{{ $task->id }}">
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-2">
                <h4 class="font-medium {{ $task->status === 'completed' ? 'line-through text-gray-500' : '' }}">
                    {{ $task->title }}
                </h4>
                <span class="badge {{ $task->statusBadgeClass }}">
                    {{ ucfirst($task->status) }}
                </span>
                @if($task->due_date)
                    <span class="badge {{ $task->due_date < now() ? 'bg-danger' : 'bg-info' }}">
                        Due: {{ $task->due_date->format('M d, Y') }}
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-600 mt-1">{{ $task->description }}</p>
            @if($task->category)
                <div class="mt-2">
                    <span class="badge bg-{{ $task->category->color ?? 'secondary' }}">
                        {{ $task->category->name }}
                    </span>
                </div>
            @endif
        </div>
        <div class="btn-group">
            <form action="{{ route('tasks.status', $task) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-success btn-sm toggle-status" title="{{ $task->status === 'completed' ? 'Mark as pending' : 'Mark as completed' }}">
                    <i class="bi {{ $task->status === 'completed' ? 'bi-check-circle-fill' : 'bi-check-circle' }}"></i>
                </button>
            </form>
            
            <button type="button" class="btn btn-outline-primary btn-sm" 
                    data-bs-toggle="modal" 
                    data-bs-target="#editTaskModal{{ $task->id }}" 
                    title="Edit task">
                <i class="bi bi-pencil"></i>
            </button>

            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline delete-task-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete task">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1" aria-labelledby="editTaskModalLabel{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel{{ $task->id }}">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="edit-task-form" action="{{ route('tasks.update', $task) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title{{ $task->id }}" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title{{ $task->id }}" name="title" value="{{ $task->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description{{ $task->id }}" class="form-label">Description</label>
                        <textarea class="form-control" id="description{{ $task->id }}" name="description" rows="3">{{ $task->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="category_id{{ $task->id }}" class="form-label">Category</label>
                        <select class="form-select" id="category_id{{ $task->id }}" name="category_id" required>
                            @foreach(Auth::user()->categories as $category)
                                <option value="{{ $category->id }}" {{ $task->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="due_date{{ $task->id }}" class="form-label">Due Date</label>
                        <input type="datetime-local" class="form-control" id="due_date{{ $task->id }}" name="due_date" 
                               value="{{ $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '' }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
