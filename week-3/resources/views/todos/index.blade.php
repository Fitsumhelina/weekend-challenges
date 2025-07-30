@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">My Todos</h1>
        <button onclick="openNewTodoModal()" class="bg-blue-600 text-white px-4 py-2 rounded">+ New Todo</button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="todo-board">
        @foreach (['not_started' => 'Not Started', 'todo' => 'To Do', 'in_progress' => 'In Progress', 'finished' => 'Finished'] as $status => $label)
            <div class="bg-gray-100 rounded p-3 shadow" data-status="{{ $status }}">
                <h2 class="text-lg font-semibold mb-2">{{ $label }}</h2>
                <div class="min-h-[150px] space-y-2 todo-column" id="column-{{ $status }}">
                    @foreach ($todosByStatus[$status] ?? [] as $todo)
                        <div class="bg-white p-3 rounded shadow todo-card" data-id="{{ $todo->id }}">
                            <h3 class="font-bold">{{ $todo->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $todo->description }}</p>
                            <p class="text-xs text-gray-400 italic">Status: {{ ucwords(str_replace('_', ' ', $todo->status)) }}</p>
                            <button onclick="editTodo({{ $todo->id }})" class="text-blue-500 text-sm mt-1">Edit</button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Modals (New + Edit) --}}
@include('todos.partials.modals')

{{-- Include SortableJS + Drag-and-Drop Script --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    const statuses = ['not_started', 'todo', 'in_progress', 'finished'];

    statuses.forEach(status => {
        new Sortable(document.getElementById('column-' + status), {
            group: 'shared',
            animation: 150,
            onEnd: function (evt) {
                const column = evt.to;
                const movedStatus = column.parentElement.getAttribute('data-status');
                const todoIds = [...column.querySelectorAll('.todo-card')].map(card => card.dataset.id);

                fetch("{{ route('todos.updateStatus') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: movedStatus,
                        todos: todoIds
                    })
                }).then(response => response.json())
                  .then(data => {
                      console.log('Updated:', data.message);
                  }).catch(err => {
                      alert('Failed to update. Try again.');
                      console.error(err);
                  });
            }
        });
    });
</script>
@endsection
