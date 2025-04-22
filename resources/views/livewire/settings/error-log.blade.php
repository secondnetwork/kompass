
<div class="align-middle inline-block min-w-full my-6">
    <div class="shadow border-b border-gray-200 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">URL</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">Message</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">IP</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              
                @forelse($logsact as $activity)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium">
                            /{{ $activity->url }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap capitalize text-sm font-medium">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-white 
                            @if($activity->status_code === '301') 
                                bg-orange-600 
                            @elseif($activity->status_code === '404') 
                                bg-red-600 
                            @elseif($activity->status_code === '200') 
                                bg-blue-600 
                            @else 
                                bg-gray-500 
                            @endif">
                                {{ __($activity->status_code) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 capitalize text-sm font-medium">
                            {{ $activity->message }}
                         </td>
                        <td class="px-4 py-3 capitalize text-sm font-medium">
                           {{ $activity->ip_address }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium">
                            {{ $activity->updated_at->isoFormat('dddd, D.M.Y HH:mm:ss') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-sm text-center text-base-content/70">No Activity Logs Found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $logsact->links('kompass::livewire.pagination') }}
    </div>
</div>