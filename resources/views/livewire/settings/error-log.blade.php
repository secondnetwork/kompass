
<div class="align-middle inline-block min-w-full my-6">


    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-base-300">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                        <button wire:click="sortBy('url')" class="flex items-center gap-1 uppercase font-medium">
                            URL
                            @if($orderBy === 'url')
                                @if($orderAsc)
                                    <x-tabler-chevron-up class="w-4 h-4" />
                                @else
                                    <x-tabler-chevron-down class="w-4 h-4" />
                                @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                        <button wire:click="sortBy('status_code')" class="flex items-center gap-1 uppercase font-medium">
                            Status
                            @if($orderBy === 'status_code')
                                @if($orderAsc)
                                    <x-tabler-chevron-up class="w-4 h-4" />
                                @else
                                    <x-tabler-chevron-down class="w-4 h-4" />
                                @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">Message</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">IP</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                        <button wire:click="sortBy('updated_at')" class="flex items-center gap-1 uppercase font-medium">
                            Date
                            @if($orderBy === 'updated_at')
                                @if($orderAsc)
                                    <x-tabler-chevron-up class="w-4 h-4" />
                                @else
                                    <x-tabler-chevron-down class="w-4 h-4" />
                                @endif
                            @endif
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-base-100 divide-y divide-gray-200">
              
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