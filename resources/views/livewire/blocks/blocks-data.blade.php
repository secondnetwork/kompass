<div>
    <div class="border-b border-gray-200 px-20 py-10 flex justify-center items-center" >
        <div class=" flex-auto">
            <span class="text-gray-400 ">df</span>

            <h2>{{$name}}</h2>
            <div class="col-span-6">



            </div>


        </div>

        <button class="flex btn gap-x-2   justify-center items-center" wire:click="saveUpdate({{$blocktemplatesId}})"><x-tabler-device-floppy class="icon-lg"/>{{ __('Save')}}</button>
    </div>
    <div class="px-20 py-10">
        <div class="bg-white p-10 shadow rounded-[1rem]">



            <x-kompass::form.input wire:model.live="name" id="name" name="name" label="name" type="text" class="mt-1 block w-full"   />
            <x-kompass::form.input wire:model.live="type" id="type" name="type" label="type" type="text" class="mt-1 block w-full"   />
            <x-kompass::form.input wire:model.live="iconclass" id="iconclass" name="iconclass" label="iconclass" type="text" class="mt-1 block w-full"   />

    <img src="{{ asset('storage/' . $icon_img_path) }}" alt="">
    <pre>
        {{$grid}}
        {{$blocktemplatesId}}
        {{$updated_at ?? ''}}
    </pre>
    </div>
    </div>
</div>


