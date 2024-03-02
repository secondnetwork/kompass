<div class="flex items-start space-x-5 border-b-slate-200 border-b pb-8">
    <div class="shrink-0 w-12">
        <img src="{{ $post->user->avatarUrl() }}" class="w-12 h-12 rounded-full">
    </div>
    <div class="grow space-y-2">
        <div class="font-bold text-lg">{{ $post->user->name }}</div>
        <div x-data="{ editing: false }" x-on:edit-cancel="editing = false">
            <div class="space-y-4" x-show="!editing">
                <p>{{ $post->body }}</p>

                <div class="flex items-center space-x-2">
                    @can('update', $post)
                        <div>
                            <button class="text-indigo-500" x-on:click="editing = true">Edit</button>
                        </div>
                    @endcan
                    @can('delete', $post)
                        <div>
                            <button class="text-indigo-500" wire:click="delete">Delete</button>
                        </div>
                    @endcan
                </div>
            </div>
            <div x-show="editing" x-cloak>
                <livewire:edit-post :post="$post" />
            </div>
        </div>
    </div>
    <div class="shrink-0 flex items-start self-stretch">
        <button class="py-1 px-3 flex items-center bg-slate-100 rounded-lg" wire:click="like">{{ $post->likes }}</button>
    </div>
</div>
