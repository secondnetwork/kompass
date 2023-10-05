<div>

    <div x-cloak id="FormAdd" x-data="{ open: @entangle('FormAdd') }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">

                <x-kompass::form.input type="text" name="title" wire:model="title" />
                <x-kompass::input-error for="title" class="mt-2" />
                <x-kompass::form.textarea wire:model="meta_description" id="name" name="Description"
                    label="Description" type="text" class="mt-1 block w-full h-[15rem]" />

                <button wire:click="addPost" class="btn btn-primary">{{ __('Save') }}</button>

            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <x-kompass::modal data="FormDelete" />
<x-kompass::action-message class="" on="status" />

    <div class="flex flex-col">
        <div class=" border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-end items-center">
            <div x-data="{ open: @entangle('FormAdd').live  }" class="flex justify-end gap-4">

                <button class="flex btn gap-x-2 justify-center items-center text-md" @click="open = true">
                    <x-tabler-square-plus stroke-width="1.5" />{{ __('New post') }}
                </button>
          </div>
        </div>

        <div class=" align-middle inline-block min-w-full ">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">



                @if ($posts->count())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            @foreach ($headers as $key => $value)
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    {{ __($value) }}
                                </th>
                            @endforeach

                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200 ">
                            @foreach ($posts as $key => $post)
                                <tr>
                                    @foreach ($data as $key => $value)
                                        <td class="px-4 whitespace-nowrap text-sm font-medium text-gray-800 bg-white">
                                            @if ($key == 0)
                                                <a wire:navigate href="/admin/posts/show/{{ $post->id }}">
                                            @endif
                                            @if ($key == 1)
                                                @switch($post->$value)
                                                    @case('published')
                                                        <span
                                                            class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <span class="relative flex h-2 w-2">
                                                                <span
                                                                    class="animate-[ping_3s_ease-in-out_infinite] absolute inline-flex h-full w-full rounded-full bg-teal-500 opacity-75"></span>
                                                                <span
                                                                    class="relative inline-flex rounded-full h-2 w-2 bg-teal-500"></span>
                                                            </span>
                                                        @break

                                                        @case('password')
                                                            <span
                                                                class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-violet-100 text-violet-800">
                                                                <span class="relative flex h-2 w-2">
                                                                    <span
                                                                        class="animate-[ping_3s_ease-in-out_infinite] absolute inline-flex h-full w-full rounded-full bg-purple-500 opacity-75"></span>
                                                                    <span
                                                                        class="relative inline-flex rounded-full h-2 w-2 bg-purple-500"></span>
                                                                </span>
                                                            @break

                                                            @default
                                                                <span
                                                                    class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                    <span class="relative flex h-2 w-2">

                                                                        <span
                                                                            class="relative inline-flex rounded-full h-2 w-2 bg-gray-500"></span>
                                                                    </span>
                                                            @endswitch
                                            @endif
                                            {{ __($post->$value) }}
                                            @if ($key == 0)
                                                </a>
                                            @endif
                                            @if ($key == 1)
                                                </span>
                                            @endif
                                        </td>
                                    @endforeach

                                    <td class="px-4 py-3 whitespace-nowrap bg-white">
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

                                            <a target="_blank" href="/{{ $post->slug }}" class="flex justify-center">
                                                <x-tabler-external-link class="cursor-pointer stroke-gray-400" />
                                            </a>

                                            <span wire:click="clone({{ $post->id }})" class="flex justify-center">
                                                <x-tabler-copy class="cursor-pointer    stroke-violet-500" />
                                            </span>

                                            <span wire:click="selectItem({{ $post->id }}, 'delete')"
                                                class="flex justify-center">
                                                <x-tabler-trash class="cursor-pointer stroke-red-500" />
                                            </span>
                                        </div>
                                    </td>
                            @endforeach
                            </tr>


                        </tbody>
                    </table>
                @else
                    <div class="h-36 text-center">{{ __('No Data') }}</div>

                @endif


            </div>
        </div>

    </div>

</div>
