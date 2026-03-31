<div x-data="{ dropdownOpen: false }" class="relative border-neutral-200 border p-1 rounded-md">

    <button @click="dropdownOpen=true"
        class="flex gap-1 text-sm font-medium w-full overflow-hidden">

        <div class="text-left w-34 ml-2">
            <span class="truncate block ">{{ auth()->user()->name }}</span>
            <span class="text-xs font-light text-neutral-400 truncate block ">{{ auth()->user()->email }}</span>
        </div>
      
        <div class="w-28">
            <x-kompass::elements.avatar :user="auth()->user()" size="w-10" />
        </div>
    
    </button>

    <div x-show="dropdownOpen" @click.away="dropdownOpen=false" x-transition:enter="ease-out duration-200"
        x-transition:enter-start="-translate-y-2" x-transition:enter-end="translate-y-0"
        x-transition:leave="ease-in duration-100" x-transition:leave-start="-translate-y-0"
        x-transition:leave-end="-translate-y-2" class="absolute bottom-14 z-50 w-full mt-12 left-0" x-cloak>
        <div class="p-1  bg-white border rounded-md shadow-md border-neutral-200/70 text-neutral-700">
            <a href="/admin/profile"
                class="relative flex gap-x-2 cursor-default select-none hover:bg-neutral-100 items-center rounded !px-2 py-1.5 text-sm outline-none transition-colors data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
                <x-tabler-user class="icon-lg" />
                <span>{{ __('Account Settings') }}</span>
            </a>

            <div class="h-px my-1 -mx-1 bg-neutral-200"></div>

            <span
                class="relative flex cursor-default select-none hover:bg-neutral-100 items-center rounded px-2 py-1.5 text-sm outline-none transition-colors focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button class="w-full flex gap-x-2 justify-center items-center" type="submit">
                        <x-tabler-logout class="icon-lg" /> {{ __('Logout') }}
                    </button>
                </form>
            </span>
        </div>
    </div>
</div>