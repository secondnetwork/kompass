<div x-show="open" @click="open = false" x-transition.duration.500ms class="fixed top-0 right-0 bottom-0 left-0 backdrop-blur-sm z-40" x-cloak></div>

<div x-show="open"  class="flex flex-col right-0 {{$w ?? 'w-2/4'}}  fixed top-0 py-4 bg-white  h-full overflow-auto z-40 shadow"
x-transition:enter="transform transition-transform duration-300" 
x-transition:enter-start="translate-x-full" 
x-transition:enter-end="translate-x-0" 
x-transition:leave="transform transition-transform duration-300" 
x-transition:leave-start="translate-x-0" 
x-transition:leave-end="translate-x-full" x-cloak>

    <div x-show="open" class="absolute inset-0 flex flex-col ">

        <div class="body-content p-8 pt-4 grid gap-4">

            <span class="flex justify-between items-center ">
            <span>
                {{$button ?? ''}} 
            </span>
              <span @click="open = false" class="cursor-pointer p-2 bg-gray-100 rounded-full hover:bg-gray-300 transition-all"><x-tabler-x /></span>
            </span>
  
            <div class="grid gap-4">{{$body}}</div>
            

        </div>

    </div>

</div>
