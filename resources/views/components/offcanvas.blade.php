<div x-show="open" @click.away="open = false" class="flex flex-col right-0 w-2/4 fixed top-0 py-4 bg-white  h-full overflow-auto z-40 shadow"
x-transition:enter="transform transition-transform duration-300" 
x-transition:enter-start="translate-x-full" 
x-transition:enter-end="translate-x-0" 
x-transition:leave="transform transition-transform duration-300" 
x-transition:leave-start="translate-x-0" 
x-transition:leave-end="translate-x-full">

    <div x-show="open" @click.away="open = false" class="absolute inset-0 flex flex-col ">


        <div class="body-content {{$class}}">

            {{$body}}

        </div>

    </div>

</div>
