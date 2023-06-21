<div>
<div x-cloak x-data="{ open: @entangle('FormDelete') }"
x-init="
$watch('open', value => {
    const body = document.body;
    if(!open) {
       body.classList.remove('h-screen');
       return body.classList.remove('overflow-hidden');
    } else {
        body.classList.add('h-screen');
        return body.classList.add('overflow-hidden');
    }
});">

  <div x-show="open"x-cloak class="bg-white mx-auto rounded shadow-lg z-50 text-left p-6 absolute left-2/4 translate-x-[-50%] translate-y-[-20%]"
  x-transition:enter="ease-out duration-300"
  x-transition:enter-start="opacity-0 scale-0"
  x-transition:enter-end="opacity-100 scale-100"
  x-transition:leave="ease-in duration-300"
  x-transition:leave-start="opacity-100 scale-100"
  x-transition:leave-end="opacity-0 scale-0">
      <div class="bg-white h-2/3">
      <div class="modal-header py-4">
          <div @click.away="open = false" type="button" class="absolute right-4 top-4 m-0 cursor-pointer">
            <x-tabler-x class="icon-lg"/>
          </div>
      </div>
      <div class="modal-body py-8">
          <h4>Do you wish to continue?</h4>
      </div>
      <div class="modal-footer flex justify-end gap-4">
          <button @click.away="open = false"type="button" class="btn-secondary" data-dismiss="modal">Cancel</button>
          <button wire:click="delete" type="button" class="btn-danger">Yes</button>
      </div>
      </div>

  </div>
  <div x-show="open" @click.away="open = false" class="absolute bg-gray-500/50 inset-0 z-10 flex items-center justify-center overflow-hidden"></div>

</div>

<div x-cloak x-data="{ open: @entangle('FormEdit') }">

<div x-show="open" @click.away="open = false" class="fixed top-0 shadow-lg h-full right-0 w-3/5 bg-white z-10 flex items-center justify-center translate-x-[0]"
x-transition:enter="ease-out duration-300"
x-transition:enter-start="opacity-0 offcanvas-0"
x-transition:enter-end="opacity-100 offcanvas-100"
x-transition:leave="ease-in duration-300"
x-transition:leave-start="opacity-100 offcanvas-100"
x-transition:leave-end="opacity-0 offcanvas-0">

    <div x-show="open" @click.away="open = false" class="absolute inset-0 p-16 flex flex-col ">

        <div class="modal-header">
            <h5 class="modal-title" id="modalFormDeletePost"></h5>
            <button @click="open = false" type="button" class="close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
          <label>Name</label>
          <input wire:model="name" type="text" class="form-control"/>
          @if ($errors->has('name'))
              <p style="color: red;">{{$errors->first('name')}}</p>
          @endif
          <label>E-Mail</label>
          <input wire:model="email" type="text" class="form-control"/></input>
          @if ($errors->has('email'))
              <p style="color: red;">{{$errors->first('email')}}</p>
          @endif

          <label>Role</label>
          <div wire:ignore>
          <select wire:model="role">
            @foreach ($roles as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
          </select>
          </div>

        </div>
        <div class="modal-footer mt-auto">
          <button wire:click="update" class="btn btn-primary">Save</button>
        </div>

    </div>

</div>
</div>



<div x-cloak x-data="{ open: @entangle('FormAdd') }">

  <div x-show="open" @click.away="open = false" class="fixed top-0 shadow-lg h-full right-0 w-3/5 bg-white z-10 flex items-center justify-center translate-x-[0]"
  x-transition:enter="ease-out duration-300"
  x-transition:enter-start="opacity-0 offcanvas-0"
  x-transition:enter-end="opacity-100 offcanvas-100"
  x-transition:leave="ease-in duration-300"
  x-transition:leave-start="opacity-100 offcanvas-100"
  x-transition:leave-end="opacity-0 offcanvas-0">

      <div x-show="open" @click.away="open = false" class="absolute inset-0 p-16 flex flex-col ">

          <div class="modal-header">
              <h5 class="modal-title" id="modalFormDeletePost"></h5>
              <button @click="open = false" type="button" class="close">
              <span aria-hidden="true">&times;</span>
              </button>
              <h1>ADD</h1>
          </div>

          <div class="modal-body">
            <label>Name</label>
            <input wire:model="name" type="text" class="form-control"/>
            @if ($errors->has('name'))
                <p style="color: red;">{{$errors->first('name')}}</p>
            @endif
            <label>E-Mail</label>
            <input wire:model="email" type="text" class="form-control"/></input>
            @if ($errors->has('email'))
                <p style="color: red;">{{$errors->first('email')}}</p>
            @endif

            <label>Role</label>
            <div wire:ignore>
            <select wire:model="role">
              <option value="" > Auswählen</option>
              @foreach ($roles as $role)
              <option value="{{ $role->id }}">{{ $role->name }}</option>
              @endforeach
            </select>
            </div>

          </div>
          <div class="modal-footer mt-auto">
            <button wire:click="addNewUser" class="btn btn-primary">Save</button>
          </div>

      </div>

  </div>
  </div>




<div class="">

  <div class="flex justify-end gap-4 my-4">
    <button class="flex btn gap-x-2 justify-center items-center text-md" wire:click="selectItem(1, 'add')"><x-tabler-user-plus stroke-width="1.5" />{{__('Add')}} {{__('User')}} </button>
</div>
  <div class="flex justify-between gap-4 my-4">



      <input wire:model.debounce.300ms="search" type="text" class="text-gray-700  h-16 py-3 px-4 my-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Search users...">


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
              @if($users->count())

              @foreach ($users as $user)
              <tr>
                <td class="px-4 py-2 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      {{-- <img class="h-12 w-12 rounded-full" src="{{ asset('storage/'.$user->profile_photo_path) }}" alt=""> --}}

                      {{-- @if ($user->profile_photo_url)
                      <img class="h-12 w-12 rounded-full" src="{{ $user->profile_photo_url }}" alt="">
                      @else
                       <div class="text-[#36424A] bg-[#FFA700] h-12 w-12 rounded-full items-center justify-center flex">{{ nameWithLastInitial($user->name) }}</div>
                      @endif --}}
                      <div class="relative block">
                        <span class="absolute inset-0 z-0 flex items-center justify-center text-[#36424A] bg-[#FFA700] rounded-full h-10 w-10 text-base">
                          {{ nameWithLastInitial($user->name) }}
                        </span>
                        <img class="absolute rounded-full h-10 w-10 z-10 items-center justify-center flex" src="{{ $user->profile_photo_url }}" alt="">
                      </div>


                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        {{$user->name}}
                      </div>
                      <div class="text-xs text-gray-500">
                        {{$user->email}}
                      </div>
                    </div>
                  </div>
                </td>
                {{-- <td class="px-4 py-2 whitespace-nowrap">
                  <div class="text-sm text-gray-900">Regional Paradigm Technician</div>
                  <div class="text-sm text-gray-500">Optimization</div>
                </td> --}}
                <td class="px-4 py-2 whitespace-nowrap">
                  @empty($user->email_verified_at)
                  <span class="px-2 inline-flex font-semibold rounded-md text-xs bg-red-300 text-red-800">
                    no Active {{$user->email_verified_at}}
                  </span>
                  @else
                  <span class="px-2 inline-flex font-semibold rounded text-xs bg-green-100 text-green-800">
                    {{ \Carbon\Carbon::parse($user->email_verified_at)->format('d.m.Y H:i') }}
                    {{-- @php
                    $timezone = config('app.timezone');

                    $date_email_verified_at \Carbon\Carbon::parse($user->email_verified_at)->tz($timezone)->format('d.m.Y H:i');
                    @endphp

                    Active {{$date_email_verified_at}} --}}
                  </span>
                  @endif

                </td>
                <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">
                    @foreach ($user->roles as $user_role)
                    {{ $user_role->name }}
                    @endforeach


                </td>
                <td class="px-4 py-2 whitespace-nowrap text-right">
                  <div class="flex justify-end items-center gap-1">
                  <span wire:click="selectItem({{ $user->id }}, 'update')" class="flex justify-center"><x-tabler-edit class="cursor-pointer stroke-blue-500"/></span>
                  <span wire:click="selectItem({{ $user->id }}, 'delete')" class="flex justify-center"><x-tabler-trash class="cursor-pointer stroke-red-500"/></span>
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
