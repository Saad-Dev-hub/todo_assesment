<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="text-xl font-semibold">Categories</h2>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                            Add Category
                        </button>
                    </div>

                    @if($categories->isEmpty())
                        <p class="text-center text-muted my-4">No categories found.</p>
                    @else
                        <div class="list-group">
                            @foreach($categories as $category)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">{{ $category->name }}</h5>
                                            <span class="badge bg-{{ $category->color }}">{{ $category->color }}</span>
                                            <small class="text-muted">{{ $category->tasks_count }} tasks</small>
                                        </div>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure? This will also delete all tasks in this category.')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Category Modal -->
                                <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">Edit Category</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('categories.update', $category) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Name</label>
                                                        <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="color" class="form-label">Color</label>
                                                        <select class="form-select" id="color" name="color" required>
                                                            <option value="primary" {{ $category->color == 'primary' ? 'selected' : '' }}>Blue</option>
                                                            <option value="secondary" {{ $category->color == 'secondary' ? 'selected' : '' }}>Gray</option>
                                                            <option value="success" {{ $category->color == 'success' ? 'selected' : '' }}>Green</option>
                                                            <option value="danger" {{ $category->color == 'danger' ? 'selected' : '' }}>Red</option>
                                                            <option value="warning" {{ $category->color == 'warning' ? 'selected' : '' }}>Yellow</option>
                                                            <option value="info" {{ $category->color == 'info' ? 'selected' : '' }}>Light Blue</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create Category Modal -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryModalLabel">Create Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="new-name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="new-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="new-color" class="form-label">Color</label>
                            <select class="form-select" id="new-color" name="color" required>
                                <option value="primary">Blue</option>
                                <option value="secondary">Gray</option>
                                <option value="success">Green</option>
                                <option value="danger">Red</option>
                                <option value="warning">Yellow</option>
                                <option value="info">Light Blue</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
