<div class="flex flex-col">

    <div x-cloak id="FormAdd" x-data="{ open: @entangle('FormAdd')}">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">

                <x-kompass::form.input label="{{ __('Title') }}" type="text" name="title" wire:model="title" />
                <x-kompass::form.textarea wire:model="meta_description" id="name" name="Description"
                    label="{{ __('Description') }}" type="text" class="mt-1 block w-full h-[15rem]" />

                @if (setting('global.multilingual'))
                <div class="mt-4">
                    <x-kompass::select wire:model="land" :searchable="false" label="{{ __('Language') }}" :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])">
                    </x-kompass::select>
                </div>
                @endif

                <button wire:click="addPost" class="btn btn-primary mt-4">{{ __('Save') }}</button>

            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <div x-cloak id="FormClone" x-data="{ open: @entangle('FormClone')}">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">               
                @if (setting('global.multilingual'))
                <div class="mt-4">
                    <x-kompass::select wire:model="cloneLand" :searchable="false" label="{{ __('Select the target language for the cloned post.') }}" :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])">
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
        <div class="flex items-end justify-between gap-4 flex-wrap p-5 bg-base-100 border border-base-300 rounded-t-xl">
            <div>
                <h6 class="font-semibold text-lg">{{ __('Posts') }}</h6>
                <p class="text-xs opacity-60">{{ __('Manage your blog posts') }}</p>
            </div>

            <div class="flex items-center gap-2 flex-wrap justify-end">
                <div class="w-full sm:w-64">
                    <x-kompass::table-search wire:model.live="search" placeholder="{{ __('Search posts...') }}" />
                </div>

                @if (setting('global.multilingual'))
                    <div class="w-40">
                        <x-kompass::select wire:model.live="land" :searchable="false" label=" " :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])->prepend(['name' => __('All Languages'), 'id' => ''])">
                        </x-kompass::select>
                    </div>
                @endif

                <button class="btn btn-primary" wire:click="$set('FormAdd', true)">
                    <x-tabler-square-plus stroke-width="1.5" />{{ __('New post') }}
                </button>
            </div>
        </div>

        <div class="align-middle inline-block min-w-full">
            <div class="overflow-hidden rounded-b-xl border border-t-0 border-base-300 bg-base-100">

                @if ($posts->count())
                    <table class="min-w-full divide-y divide-base-200 [&_tbody_tr:hover_td]:bg-base-200/50">
                        <thead class="bg-base-200">
                            <tr>
                                @foreach ($headers as $key => $value)
                                    @php $sortField = $value === 'Updated' ? 'updated_at' : $value; @endphp
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">

                                        @if(in_array($value, ['title', 'Updated', 'status', 'land']))
                                            <button wire:click="sortBy('{{ $sortField }}')" class="flex items-center gap-1 uppercase font-medium">
                                                {{ __($value) }}
                                                @if($orderBy === $sortField)
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

                <tbody class="bg-base-100 divide-y divide-base-200">
                    @foreach ($posts as $key => $post)
                        <tr wire:key="post-{{ $post->id }}">
                            @foreach ($data as $column)
                                <td class="px-4 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">
                                    @if ($column == 'title')
                                        <a wire:navigate href="/admin/posts/show/{{ $post->id }}" class="flex items-center gap-3">
                                            <div class="w-16 h-11 shrink-0 rounded overflow-hidden bg-base-300 flex items-center justify-center my-2">
                                                @if ($post->thumbnailFile)
                                                    <img src="{{ asset('storage/' . $post->thumbnailFile->path . '/' . $post->thumbnailFile->slug . '.' . $post->thumbnailFile->extension) }}" alt="{{ $post->thumbnailFile->alt ?? $post->title }}" class="w-full h-full object-cover" />
                                                @else
                                                    <x-tabler-photo class="w-5 h-5 text-base-content/30" />
                                                @endif
                                            </div>
                                            <span>{{ $post->title }}</span>
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

                    <x-kompass::table-footer :paginator="$posts" />
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
