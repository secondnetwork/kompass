<div class="flex flex-col">

    <div x-cloak id="FormAdd" x-data="{ open: @entangle('FormAdd').live }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">

                <x-kompass::form.input label="{{ __('Title') }}" type="text" name="title" wire:model="title" />
                <x-kompass::form.textarea wire:model="meta_description" id="name" name="Description"
                    label="{{ __('Description') }}" type="text" class="mt-1 block w-full h-[15rem]" />

                @if (setting('global.multilingual'))
                <div class="mt-4">
                    <x-kompass::select wire:model="land" label="{{ __('Language') }}" :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])">
                    </x-kompass::select>
                </div>
                @endif

                <button wire:click="addPost" class="btn btn-primary mt-4">{{ __('Save') }}</button>

            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <div x-cloak id="FormClone" x-data="{ open: @entangle('FormClone').live }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">
                <h3 class="text-lg font-bold mb-4">{{ __('Clone Post') }}</h3>
                
                @if (setting('global.multilingual'))
                <p class="mb-4 text-sm text-base-content/70">{{ __('Select the target language for the cloned post.') }}</p>

                <div class="mt-4">
                    <x-kompass::select wire:model="cloneLand" label="{{ __('Language') }}" :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])">
                    </x-kompass::select>
                </div>
                @else
                <p class="mb-4 text-sm text-base-content/70">{{ __('Are you sure you want to clone this post?') }}</p>
                @endif

                <button wire:click="clonePage" class="btn btn-primary mt-4">{{ __('Clone') }}</button>
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <x-kompass::modal data="FormDelete" />
    <x-kompass::action-message class="" on="status" />

    <div class="flex flex-col">
        <div class=" border-gray-200 whitespace-nowrap text-sm flex gap-8 justify-between items-center">
            
            <div class="w-full">
                <x-kompass::form.input type="text" name="search" wire:model.live="search" placeholder="{{ __('Search posts...') }}" />
            </div>

            <div class="flex justify-end gap-4 items-center">
                @if (setting('global.multilingual'))
                <div class="w-44">
                    <x-kompass::select wire:model.live="land" label=" " :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])->prepend(['name' => __('All Languages'), 'id' => ''])">
                    </x-kompass::select>
                </div>
                @endif

                <button class="btn btn-primary" wire:click="$set('FormAdd', true)">
                    <x-tabler-square-plus stroke-width="1.5" />{{ __('New post') }}
                </button>
          </div>
        </div>
        
        <div class="divider"></div>

        <div class=" align-middle inline-block min-w-full ">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">



                @if ($posts->count())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-base-300">
                            <tr>
                                @foreach ($headers as $key => $value)
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                                        
                                        @if($value == 'title' || $value == 'updated_at')
                                            <button wire:click="sortBy('{{ $value }}')" class="flex items-center gap-1 uppercase font-medium">
                                                {{ __($value) }}
                                                @if($orderBy === $value)
                                                    @if($orderAsc)
                                                        <x-tabler-chevron-up class="w-4 h-4" />
                                                    @else
                                                        <x-tabler-chevron-down class="w-4 h-4" />
                                                    @endif
                                                @endif
                                            </button>
                                        @else
                                            {{ __($value) }}
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                <tbody class="bg-base-100 divide-y divide-gray-200">
                    @foreach ($posts as $key => $post)
                        <tr wire:key="post-{{ $post->id }}">
                            @foreach ($data as $column)
                                <td class="px-4 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">
                                    @if ($column == 'title')
                                        <a wire:navigate href="/admin/posts/show/{{ $post->id }}">
                                            {{ __($post->title) }}
                                        </a>
                                    @elseif ($column == 'status')
                                        @switch($post->status)
                                            @case('published')
                                                <span class="badge badge-sm border-green-200 bg-green-100 text-green-800">
                                                    <span class="relative flex h-2 w-2">
                                                        <span class="animate-[ping_3s_ease-in-out_infinite] absolute inline-flex h-full w-full rounded-full bg-teal-500 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-teal-500"></span>
                                                    </span>
                                                    {{ __('published') }}
                                                </span>
                                                @break

                                            @case('password')
                                                <span class="badge badge-sm border-violet-200 bg-violet-100 text-violet-800">
                                                    <span class="relative flex h-2 w-2">
                                                        <span class="animate-[ping_3s_ease-in-out_infinite] absolute inline-flex h-full w-full rounded-full bg-purple-500 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-500"></span>
                                                    </span>
                                                    {{ __('password') }}
                                                </span>
                                                @break

                                            @default
                                                <span class="badge badge-sm border-gray-300 bg-gray-100 text-gray-800">
                                                    <span class="relative flex h-2 w-2">
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-gray-500"></span>
                                                    </span>
                                                    {{ __('draft') }}
                                                </span>
                                        @endswitch
                                    @elseif ($column == 'land')
                                        @if ($post->land)
                                            <span class="badge badge-sm border-blue-200 bg-blue-100 text-blue-800">
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                                {{ strtoupper($post->land) }}
                                            </span>
                                        @endif
                                    @elseif ($column == 'updated_at')
                                        {{ $post->updated_at }}
                                    @else
                                        {{ $post->$column }}
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-4 py-3 whitespace-nowrap bg-base-100">
                                <div class="flex justify-end items-center gap-1">

                                    <a wire:navigate href="/admin/posts/show/{{ $post->id }}" class="flex justify-center">
                                        <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                                    </a>

                                    @if ($post->status == 'published')
                                        <span wire:click="status({{ $post->id }}, 'draft')">
                                            <x-tabler-eye class="cursor-pointer stroke-gray-400" />
                                        </span>
                                    @else
                                        <span wire:click="status({{ $post->id }}, 'published')">
                                            <x-tabler-eye-off class="cursor-pointer stroke-red-500" />
                                        </span>
                                    @endif

                                    <a target="_blank" href="/blog/{{ $post->slug }}" class="flex justify-center">
                                        <x-tabler-external-link class="cursor-pointer stroke-gray-400" />
                                    </a>

                                    <span wire:click="selectItem({{ $post->id }}, 'clone')" class="flex justify-center">
                                        <x-tabler-copy class="cursor-pointer    stroke-violet-500" />
                                    </span>

                                    <span wire:click="selectItem({{ $post->id }}, 'delete')"
                                        class="flex justify-center">
                                        <x-tabler-trash class="cursor-pointer stroke-red-500" />
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                    </table>
                @else
                    <div class="min-h-[60vh] flex flex-col items-center justify-center">
                        <x-tabler-news stroke-width="1.5" class="w-16 h-16 mb-2 text-brand-500" />
                        <div class="text-lg font-semibold">{{ __('No Data') }}</div>
                    </div>

                @endif


            </div>
        </div>

    </div>

</div>
