@props([
    'id' => 'text',
    'data' => '',

])

<div x-show="open" x-data="{ open: @entangle('FormDelete') }" 
x-cloak class="fixed grid overflow-y-auto overflow-x-hidden place-items-center md:inset-0 h-modal sm:h-full z-50" id="medium-modal"
x-transition:enter="transform transition-transform duration-300" 
x-transition:enter-start="-translate-y-full" 
x-transition:enter-end="translate-y-0" 
x-transition:leave="transform transition-transform duration-300" 
x-transition:leave-start="translate-y-0" 
x-transition:leave-end="-translate-y-full">
    

    <div class="relative px-4 w-full max-w-4xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow ">
            <!-- Modal header -->
            <div class="flex justify-between items-center p-5 rounded-t border-b dark:border-gray-600">
                <h3 class="text-xl font-medium text-gray-900  flex items-center">
                     <x-tabler-alert-triangle class="cursor-pointer stroke-current h-8 w-8 text-yellow-500"/> Remove 
                </h3>
                <button @click="open = false" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="medium-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    {{__('Are you sure you want to delete the selected resources?')}}
                </p>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center justify-end  p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                <button @click="open = false" type="button" class="btn-secondary bg-slate-400" data-dismiss="modal">{{ __('Cancel')}}</button>
                <button wire:click="delete" type="button" class="btn-danger bg-red-500">{{ __('Delete')}}</button>
                {{-- <button data-modal-toggle="medium-modal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>
                <button data-modal-toggle="medium-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Decline</button>--}}
            </div> 
        </div>
    </div>
</div>