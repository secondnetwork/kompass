<div class="relative" x-data="{ dropbox: false }" x-init="() => {
        $el.querySelector('input').value = '{{ $itemblocks->getMeta($metaKey) }}';
    }"
>
    <span class="text-sm flex font-bold gap-1">
        {{ $label }} <x-tabler-circle-dashed-plus class="cursor-pointer" @click="dropbox = !dropbox" /> <p class="text-blue-700">{{ $itemblocks->getMeta($metaKey) }}</p>
    </span>

    <div x-show="dropbox"  @click.outside="listcssClass = false; listcssId = false, dropbox = false" class="absolute z-10 left-0 top-6 flex items-center gap-x-2 bg-gray-100 shadow w-60 p-2 mb-2" x-data="{ listcssClass: false, listcssId: false, value: '{{ $itemblocks->getMeta($metaKey) }}' }">

    
        <input type="text" class="border border-gray-400  text-sm font-normal" x-model="value"
                wire:model.debounce.500ms="newName"
               x-on:keydown.enter=" $wire.updateMeta({{ $itemblocks->id }}, value)"
        >

        <button @click.prevent x-on:click="listcssClass = false; listcssId = false; $wire.updateMeta({{ $itemblocks->id }}, value)" type="button" class="cursor-pointer ">
            <x-tabler-device-floppy class="size-5 text-blue-600" stroke-width="2" />
        </button>

        @if ($metaKey == 'css-classname')
        <x-tabler-list @click="listcssClass = !listcssClass" 
        class="cursor-pointer" stroke-width="2" />
        <ul x-show="listcssClass" class="absolute z-10 left-0 top-11 flex max-h-44 w-full flex-col overflow-hidden overflow-y-auto border-slate-300 bg-white py-1.5 dark:border-slate-700 dark:bg-slate-800 rounded-md border-2" >
            @foreach ($cssClasses as $item)
            <li x-on:click="listcssClass = false; $wire.updateMeta({{ $itemblocks->id }}, '{{ $item['name'] }}')" class="combobox-option inline-flex cursor-pointer justify-between gap-6 bg-white px-4 py-2 text-sm text-slate-700 hover:bg-slate-800/5 hover:text-black focus-visible:bg-slate-800/5 focus-visible:text-black focus-visible:outline-none dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-100/5 dark:hover:text-white dark:focus-visible:bg-slate-100/10 dark:focus-visible:text-white"  >
                {{ $item['name'] }}
            </li>

            @endforeach
        </ul>
        @else    
        <x-tabler-list @click="listcssId = !listcssId" 
        class="cursor-pointer" stroke-width="2" />
        <ul x-show="listcssId" class="absolute z-10 left-0 top-11 flex max-h-44 w-full flex-col overflow-hidden overflow-y-auto border-slate-300 bg-white py-1.5 dark:border-slate-700 dark:bg-slate-800 rounded-md border-2" >
            @foreach ($idAnchor as $item)
            <li x-on:click="listcssId = false; $wire.updateMeta({{ $itemblocks->id }}, '{{ $item['name'] }}')" class="combobox-option inline-flex cursor-pointer justify-between gap-6 bg-white px-4 py-2 text-sm text-slate-700 hover:bg-slate-800/5 hover:text-black focus-visible:bg-slate-800/5 focus-visible:text-black focus-visible:outline-none dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-100/5 dark:hover:text-white dark:focus-visible:bg-slate-100/10 dark:focus-visible:text-white" >
                {{ $item['name'] }}
            </li>

            @endforeach
        </ul>
        @endif
      
    </div>
  

</div>

