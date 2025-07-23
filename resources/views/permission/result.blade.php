<div class="overflow-x-auto rounded-lg shadow-md">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
            <tr>
                <th class="py-3 px-6 text-left">Permission Name</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @forelse ($permissions as $permission)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $permission->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="py-4 px-6 text-center text-gray-500">No permissions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination Links --}}
<div class="mt-6">
    {{ $permissions->links() }}
</div>
