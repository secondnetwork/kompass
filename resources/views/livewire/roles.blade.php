<div>

      @if (session()->has('message'))

          <div class="alert alert-success">

              {{ session('message') }}

          </div>

      @endif

    <x-kompass::modal data="FormDelete" />

    <div x-cloak x-data="{ open: @entangle('FormEdit') }">
      <x-kompass::offcanvas :w="'w-2/6'">
        <x-slot name="body">
          <div class="modal-body">
            <label>Role Name</label>
            <input wire:model="name" type="text" class="form-control"/>
            @if ($errors->has('name'))
                <p style="color: red;">{{$errors->first('name')}}</p>
            @endif
            <label>Role Name</label>
            <input wire:model="display_name" type="text" class="form-control"/>

            <label>{{ __('Description') }}</label>

            <textarea wire:model="description" id="" cols="30" rows="10"></textarea>
  
            
          </div>
          <div class="modal-footer mt-auto">
            <button wire:click="update" class="btn btn-primary">Save</button>
          </div>
        </x-slot>

      </x-kompass::offcanvas>
    </div>

    <div x-cloak x-data="{ open: @entangle('FormAdd') }">
      <x-kompass::offcanvas :w="'w-2/6'">
        <x-slot name="body">
          <label>Role Name</label>
          <input wire:model="name" type="text" class="form-control"/>
          @if ($errors->has('name'))
              <p style="color: red;">{{$errors->first('name')}}</p>
          @endif
          <label>Role Name</label>
          <input wire:model="display_name" type="text" class="form-control"/>

          <label>{{ __('Description') }}</label>

          <textarea wire:model="description" id="" cols="30" rows="10"></textarea>

          
          <div class="modal-footer mt-auto">
            <button wire:click="addNew" class="btn btn-primary">Save</button>
          </div>
        </x-slot>
      </x-kompass::offcanvas>
    </div>


    <div class="flex flex-col">

      <div class="flex justify-end gap-4 my-8">
        <button class="flex btn gap-x-2 justify-center items-center text-md" wire:click="selectItem(1, 'add')"><x-tabler-lock-access stroke-width="1.5" />{{__('Add')}}</button>
    </div>

  <div class=" align-middle inline-block min-w-full ">
    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">


          <table class="min-w-full divide-y divide-gray-200">
            <thead class=" rounded">
                    @foreach ($headers as $key => $value )
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"> {{ $value }} </th>
                    @endforeach

                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                @if($roles->count())
                @foreach ($roles as $key => $role )


                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap">
                          <div class="flex items-center">

                            <div>
                              <div class="text-sm font-medium text-gray-900">
                                {{$role->display_name}}
                              </div>
                            </div>

                          </div>
                        </td>

                        <td class="px-4 py-2 whitespace-nowrap text-right">
                          <div class="flex justify-end items-center gap-1">
                          <span wire:click="selectItem({{ $role->id }}, 'update')" class="flex  justify-center "><x-tabler-edit class="cursor-pointer stroke-blue-500"/></span>
                          <span wire:click="selectItem({{ $role->id }}, 'delete')" class="flex  justify-center "><x-tabler-trash class="cursor-pointer stroke-red-500"/></span>
                          </div>
                        </td>
                      </tr>
                @endforeach


                  @else
                  <tr>
                      <td>{{__('No Data')}}</td>
                  </tr>
                  @endif
                  <!-- More people... -->
                </tbody>
              </table>

            </div>
          </div>

      </div>
    </div>
