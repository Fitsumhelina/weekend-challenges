<!-- New Todo Modal -->
<div id="newTodoModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white w-full max-w-md p-6 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Add New Todo</h2>
        <form action="{{ route('todos.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block">Title</label>
                <input type="text" name="title" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block">Description</label>
                <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            <div class="mb-4">
                <label class="block">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="not_started">Not Started</option>
                    <option value="todo">To Do</option>
                    <option value="in_progress">In Progress</option>
                    <option value="finished">Finished</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeNewTodoModal()" class="px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openNewTodoModal() {
        document.getElementById('newTodoModal').classList.remove('hidden');
    }

    function closeNewTodoModal() {
        document.getElementById('newTodoModal').classList.add('hidden');
    }
</script>
