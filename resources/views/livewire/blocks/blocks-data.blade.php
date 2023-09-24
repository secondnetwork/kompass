<div>
    <div class="border-b border-gray-200 px-20 py-10 flex justify-center items-center" >
        <div class=" flex-auto">
            <span class="text-gray-400 ">df</span>

            <h2>{{$data->name}}</h2>
            <div class="col-span-6">



            </div>


        </div>

        <button class="flex btn gap-x-2   justify-center items-center" wire:click="saveUpdate({{$data->id}})"><x-tabler-device-floppy class="icon-lg"/>{{ __('Save')}}</button>
    </div>
    <div class="px-20 py-10">
        <div class="bg-white p-10 shadow rounded-[1rem]">



            <x-kompass::form.input wire:model="data.name" id="name" name="name" label="name" type="text" class="mt-1 block w-full"   />
            {{-- <x-kompass::form.textarea wire:model="dataarray.meta_description" id="name" name="title" label="Description" type="text" class="mt-1 block w-full"   /> --}}


    <img src="{{$data->thumbnails}}" alt="">
    <pre>
        {{$data->content}}
        {{$data->layout}}
        {{$data->created_at}}
        {{$data->updated_at}}
    </pre>
    </div>
    </div>
</div>


