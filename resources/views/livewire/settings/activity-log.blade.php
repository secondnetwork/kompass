<div class="align-middle inline-block min-w-full my-6">
    <div class="shadow border-b border-gray-200 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created At</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
        
                @forelse($logsact as $activity)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap capitalize text-sm font-medium">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-white 
                            @if($activity->description === 'updated') 
                                bg-orange-600 
                            @elseif($activity->description === 'deleted') 
                                bg-red-600 
                            @elseif($activity->description === 'created') 
                                bg-blue-600 
                            @else 
                                bg-gray-500 
                            @endif">
                                {{ __($activity->description) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 capitalize text-sm font-medium">
                            {{ optional($activity->causer)->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 capitalize text-sm font-medium">
                            ID: {{ $activity->subject_id }} | {{ $activity->subject_type }}  
                        </td>
                        <td class="px-4 py-3 text-sm font-medium">
                            {{ $activity->updated_at->isoFormat('dddd, D.M.Y HH:mm:ss') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-sm text-center text-gray-500">No Activity Logs Found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $logsact->links('kompass::livewire.pagination') }}
    </div>
</div>