<div class="overflow-x-auto rounded-lg shadow-md">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal hidden md:table-header-group">
            <tr>
                <th class="py-3 px-6 text-left">ID</th>
                <th class="py-3 px-6 text-left">Name</th>
                <th class="py-3 px-6 text-left">Email</th>
                <th class="py-3 px-6 text-left">Roles</th>
                <th class="py-3 px-6 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @forelse ($users as $user)
                <tr class="border-b border-gray-200 hover:bg-gray-100 flex flex-col md:table-row md:flex-row md:w-auto w-full">
                    <td class="py-3 px-6 text-left whitespace-nowrap md:table-cell block">
                        <span class="md:hidden font-semibold">ID: </span>{{ $user->id }}
                    </td>
                    <td class="py-3 px-6 text-left whitespace-nowrap md:table-cell block">
                        <span class="md:hidden font-semibold">Name: </span>{{ $user->name }}
                    </td>
                    <td class="py-3 px-6 text-left md:table-cell block">
                        <span class="md:hidden font-semibold">Email: </span>{{ $user->email }}
                    </td>
                    <td class="py-3 px-6 text-left md:table-cell block">
                        <span class="md:hidden font-semibold">Roles: </span>
                        <div class="flex flex-wrap gap-2">
                            @forelse ($user->roles as $role)
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="text-gray-500 text-xs">No roles assigned.</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center md:table-cell block">
                        <span class="md:hidden font-semibold">Actions: </span>
                        <div class="flex item-center justify-center space-x-2">
                            @can('view user')
                                <button  type="button"
                                        class="view-user-btn w-8 h-8 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 flex items-center justify-center transition duration-300" 
                                        data-id="{{ $user->id }}" title="View">
                                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            @endcan
                            @can('update user')
                                <button type="button" 
                                        class="edit-user-btn w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-200 flex items-center justify-center transition duration-300" 
                                        data-id="{{ $user->id }}" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                            @endcan
                            @can('delete user')
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                             class="delete-user-btn w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center transition duration-300" 
                                             title="Delete" data-id="{{ $user->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-4 px-6 text-center text-gray-500">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination Links --}}
<div class="mt-6">
    {{ $users->links() }}
</div>
