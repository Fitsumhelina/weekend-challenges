<div class="overflow-x-auto rounded-lg shadow-md">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal hidden md:table-header-group">
            <tr>
                <th class="py-3 px-6 text-left">ID</th>
                <th class="py-3 px-6 text-left">Role Name</th>
                <th class="py-3 px-6 text-left">Permissions</th>
                <th class="py-3 px-6 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @forelse ($roles as $role)
                <tr class="border-b border-gray-200 hover:bg-gray-100 flex flex-col md:table-row md:flex-row md:items-center w-full md:w-auto">
                    <td class="py-3 px-6 text-left whitespace-nowrap md:table-cell flex justify-between md:block">
                        <span class="font-semibold md:hidden">ID:</span>
                        {{ $role->id }}
                    </td>
                    <td class="py-3 px-6 text-left whitespace-nowrap md:table-cell flex justify-between md:block">
                        <span class="font-semibold md:hidden">Role Name:</span>
                        {{ $role->name }}
                    </td>
                    <td class="py-3 px-6 text-left md:table-cell flex flex-col md:block">
                        <span class="font-semibold md:hidden mb-1">Permissions:</span>
                        <div class="flex flex-wrap gap-2">
                            @forelse ($role->permissions as $permission)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                    {{ $permission->name }}
                                </span>
                            @empty
                                <span class="text-gray-500 text-xs">No permissions assigned.</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center md:table-cell flex justify-between md:block">
                        <span class="font-semibold md:hidden">Actions:</span>
                        <div class="flex item-center justify-center space-x-2">
                            @can('update role')
                                <button class="role-edit-btn w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-200 flex items-center justify-center transition duration-300" data-id="{{ $role->id }}" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                            @endcan
                          @can('delete role')
                            <form action="{{ route('role.destroy', $role->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="role-delete-btn w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center transition duration-300" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                         @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-4 px-6 text-center text-gray-500">No roles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination Links --}}
@if(method_exists($roles, 'links'))
<div class="mt-6">
    {{ $roles->links() }}
</div>
@endif
