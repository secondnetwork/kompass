<div x-data="{dropdownOpen: false}" class="relative">

    <button @click="dropdownOpen=true" class="inline-flex items-center justify-center text-sm font-medium">
                        
        <span class="flex flex-col items-start flex-shrink-0 h-full mx-2 leading-none translate-y-px">
            <span>{{ auth()->user()->name }}</span>
            <span class="text-xs font-light text-neutral-400">{{auth()->user()->email}}</span>
        </span>
        <div class="relative rounded-full pl-1 h-10 w-10 flex items-center justify-center object-cover">
          <span class="absolute inset-0 z-0 flex items-center justify-center text-[#36424A] bg-[#FFA700] rounded-full text-base">
            {{ nameWithLastInitial(auth()->user()->name) }}
          </span>
          <img class="absolute rounded-full h-10 w-10 z-10 items-center justify-center flex" src="{{ Auth::user()->profile_photo_url }}" alt="">
      </div>          
    </button>

    <div x-show="dropdownOpen" 
        @click.away="dropdownOpen=false"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="-translate-y-2"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="ease-in duration-100" 
        x-transition:leave-start="-translate-y-0" 
        x-transition:leave-end="-translate-y-2"
        class="absolute top-0 z-50 w-56 mt-12 right-0"
        x-cloak>
        <div class="p-1  bg-white border rounded-md shadow-md border-neutral-200/70 text-neutral-700">
            {{-- <div class="px-2 py-1.5 text-sm font-semibold">{{ __('Account Settings') }}</div> --}}
            {{-- <div class="h-px my-1 -mx-1 bg-neutral-200"></div> --}}
            <a href="/admin/profile" class="relative flex gap-x-2 cursor-default select-none hover:bg-neutral-100 items-center rounded px-2 py-1.5 text-sm outline-none transition-colors data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
              <x-tabler-user class="icon-lg"/> 
              <span>{{ __('Account Settings') }}</span>
            </a>
           
            <div class="h-px my-1 -mx-1 bg-neutral-200"></div>

            <span class="relative flex cursor-default select-none hover:bg-neutral-100 items-center rounded px-2 py-1.5 text-sm outline-none transition-colors focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                
                <button class="w-full flex gap-x-2 justify-center items-center" type="submit">
                  <x-tabler-logout class="icon-lg"/> {{ __('Logout') }}
                </button>
              </form>
            </span>
        </div>
    </div>
</div>