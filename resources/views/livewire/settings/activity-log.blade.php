<div class="min-w-full">

    <div>
        <table class="min-w-full divide-y divide-base-200 [&_tbody_tr:hover_td]:bg-base-200/50">
            <thead class="bg-base-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                        <button wire:click="sortBy('description')" class="flex items-center gap-1 uppercase font-medium">
                            Action
                            @if($orderBy === 'description')
                                @if($orderAsc)
                                    <x-tabler-chevron-up class="w-4 h-4" />
                                @else
                                    <x-tabler-chevron-down class="w-4 h-4" />
                                @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">User</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">Subject Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                        <button wire:click="sortBy('updated_at')" class="flex items-center gap-1 uppercase font-medium">
                            Created At
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
            <tbody class="bg-base-100 divide-y divide-base-200">

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
                        <td colspan="4" class="px-4 py-3 text-sm text-center text-base-content/70">No Activity Logs Found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-kompass::table-footer :paginator="$logsact" />
    </div>
</div>