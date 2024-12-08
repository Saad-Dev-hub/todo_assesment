<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tasks') }}
            </h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                <i class="bi bi-plus-lg"></i> New Task
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-12 gap-6">
                <!-- Filters Sidebar -->
                <div class="col-span-12 md:col-span-3">
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h3 class="text-lg font-medium mb-4">Filters</h3>
                        <form id="filterForm" class="space-y-4">
                            <div class="form-group">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Search tasks...">
                            </div>

                            <div class="form-group">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                                <button type="button" id="clearFilters" class="btn btn-outline-secondary w-100">Clear Filters</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-span-12 md:col-span-9">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div id="tasksList" class="space-y-4">
                                @include('tasks._list', ['tasks' => $tasks])
                            </div>
                            <div id="pagination" class="mt-4">
                                {{ $tasks->links() }}
                            </div>
                            <div id="alertContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Task Modal -->
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTaskModalLabel">Create New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createTaskForm" action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="datetime-local" class="form-control" id="due_date" name="due_date">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create task form handler
            const createForm = document.getElementById('createTaskForm');
            const createModal = document.getElementById('createTaskModal');
            const modalInstance = new bootstrap.Modal(createModal);

            if (createForm) {
                createForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const submitBtn = this.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Creating...';

                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: new FormData(this)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close modal first
                            modalInstance.hide();
                            
                            // Reset form
                            this.reset();
                            
                            // Reload the page
                            window.location.reload();
                        } else {
                            alert(data.message || 'Failed to create task');
                        }
                    })
                    .catch(error => {
                        alert('Failed to create task. Please try again.');
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Create Task';
                    });
                });
            }

            // Clear filters
            const clearBtn = document.getElementById('clearFilters');
            if (clearBtn) {
                clearBtn.onclick = function() {
                    const search = document.getElementById('search');
                    const category = document.getElementById('category_id');
                    const status = document.getElementById('status');
                    
                    if (search) search.value = '';
                    if (category) category.value = '';
                    if (status) status.value = '';
                    
                    window.location = window.location.pathname;
                    return false;
                };
            }
        });
    </script>
    @endpush
</x-app-layout>
