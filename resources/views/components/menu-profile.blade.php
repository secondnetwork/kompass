        <div class="flex gap-4">
          <div id="navheader"></div>
         
          <div x-data="{ open: false }" class="relative z-10 transition-all">
            <div @click="open = true">
              <div class="header__avatar flex items-center justify-center cursor-pointer">
               <div class="mr-4">
                      <div class="text-sm font-semibold text-gray-900">
                        {{auth()->user()->name}}
                      </div>
                      <div class="text-xs text-gray-500">
                        {{auth()->user()->email}}
                      </div>
               </div>
                <div class="relative rounded-full h-10 w-10 flex items-center justify-center object-cover">
                  <span class="absolute inset-0 z-0 flex items-center justify-center text-[#36424A] bg-[#FFA700] rounded-full text-base">
                    {{ nameWithLastInitial(auth()->user()->name) }}
                  </span>
                  <img class="absolute rounded-full h-10 w-10 z-10 items-center justify-center flex" src="{{ Auth::user()->profile_photo_url }}" alt="">
              </div>

            </div>
            </div>  
            <div x-cloak x-show.transition="open" @click.away="open = false" @keydown.escape.window="open = false" class="absolute right-0 z-10 w-[20rem] mt-4 bg-white shadow-lg rounded overflow-hidden "
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-300" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0">
              <nav>
                  <ul class="text-sm text-gray-600 px-6 py-4">
                      <li class="flex items-center py-4">
                        <strong class="text-base">{{ auth()->user()->name }}</strong>
                      </li>
                      <li class="">
                        <a class="py-2 flex items-center w-full rounded font-semibold transition duration-150 hover:bg-gray-200 focus:outline-none focus:bg-gray-100 focus:text-indigo-600 focus:underline" href="/admin/profile" title="Profile"><x-tabler-user class="icon-lg"/> <span>{{__('Profile')}}</span></a>
                      </li>
                      <li class="flex items-center py-4">
                        <div class="w-full">
                        <form method="POST" action="{{ route('logout') }}">
                          @csrf
                          
                          <button class="w-full flex gap-x-2   justify-center items-center" type="submit">
                            <x-tabler-logout class="icon-lg"/> {{ __('Logout') }}
                          </button>
                          </form>
                        </div>
                      </li>
                  </ul>
              </nav>
          </div>
          </div>
      </div>