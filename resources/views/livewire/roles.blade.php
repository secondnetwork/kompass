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
          <div class="modal-body grid gap-4">
            <x-kompass::input wire:model="display_name" label="Display Name" />
            
            <x-kompass::input wire:model="name" label="Role Name" />

            <button wire:click="createOrUpdateRole" class="btn btn-primary">{{ __('Save') }}</button>
          </div>
        </x-slot>

      </x-kompass::offcanvas>
    </div>



    <div class="flex flex-col">

      <div class="flex justify-end gap-4 py-4">
        <button class="btn btn-primary" wire:click="selectItem('', 'add')"><x-tabler-lock-access stroke-width="1.5" />{{__('Add')}}</button>
    </div>

  <div class=" align-middle inline-block min-w-full ">
    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">


            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-base-300">
                  @foreach ($headers as $key => $value)
                      <th scope="col"
                          class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                          {{ __($value) }}
                      </th>
                  @endforeach

              </thead>

                <tbody class="bg-base-100 divide-y divide-gray-200">
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
                      <td class="flex items-center gap-2"><x-tabler-lock-access stroke-width="1.5" class="w-5 h-5" /> <span class="font-semibold">{{__('No Data')}}</span></td>
                  </tr>
                  @endif
                  <!-- More people... -->
                </tbody>
              </table>

            </div>
          </div>

      </div>
    </div>
