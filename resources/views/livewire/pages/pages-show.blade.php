<div>


    <div x-data="{ open: @entangle('FormAdjustments') }">
        <x-kompass::offcanvas :w="'w-1/3'" class="p-8 grid gap-4">
            <x-slot name="body">
                <div>
                    <strong class="text-gray-600">{{ __('Page Attributes') }}</strong></br>
                    <strong class="text-gray-600">Letztes Update:</strong> {{ $page->updated_at }}</br>
                    <div x-data="app()" x-init="[initDate(), getNoOfDays()]" x-cloak>

                        <div class="mb-4">
                            <label for="datepicker" class="font-bold mb-1 text-gray-700 block">Datum</label>
                            <div class="relative">
                                <input type="hidden" name="date" x-ref="date" :value="datepickerValue" />
                                <input type="text" x-on:click="showDatepicker = !showDatepicker"
                                    x-model="datepickerValue" x-on:keydown.escape="showDatepicker = false"
                                    class="w-full pl-4 pr-10 py-3 leading-none rounded-lg shadow-sm focus:outline-none text-gray-600 font-medium focus:ring focus:ring-blue-600 focus:ring-opacity-50"
                                    placeholder="Select date" readonly />

                                <div class="absolute top-0 right-0 px-3 py-2">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                <div class="bg-white mt-12 rounded-lg shadow p-4 absolute top-0 left-0"
                                    style="width: 17rem" x-show.transition="showDatepicker"
                                    @click.away="showDatepicker = false">
                                    <div class="flex justify-between items-center mb-2">
                                        <div>
                                            <span x-text="MONTH_NAMES[month]"
                                                class="text-lg font-bold text-gray-800"></span>
                                            <span x-text="year" class="ml-1 text-lg text-gray-600 font-normal"></span>
                                        </div>
                                        <div>
                                            <button type="button"
                                                class="focus:outline-none focus:shadow-outline transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-100 p-1 rounded-full"
                                                @click="if (month == 0) {
												year--;
												month = 12;
											} month--; getNoOfDays()">
                                                <svg class="h-6 w-6 text-gray-400 inline-flex" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <button type="button"
                                                class="focus:outline-none focus:shadow-outline transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-100 p-1 rounded-full"
                                                @click="if (month == 11) {
												month = 0; 
												year++;
											} else {
												month++; 
											} getNoOfDays()">
                                                <svg class="h-6 w-6 text-gray-400 inline-flex" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap mb-3 -mx-1">
                                        <template x-for="(day, index) in DAYS" :key="index">
                                            <div style="width: 14.26%" class="px-0.5">
                                                <div x-text="day"
                                                    class="text-gray-800 font-medium text-center text-xs"></div>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="flex flex-wrap -mx-1">
                                        <template x-for="blankday in blankdays">
                                            <div style="width: 14.28%"
                                                class="text-center border p-1 border-transparent text-sm"></div>
                                        </template>
                                        <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                                            <div style="width: 14.28%" class="px-1 mb-1">
                                                <div @click="getDateValue(date)" x-text="date"
                                                    class="cursor-pointer text-center text-sm leading-none rounded-full leading-loose transition ease-in-out duration-100"
                                                    :class="{
                                                        'bg-indigo-200': isToday(date) == true,
                                                        'text-gray-600 hover:bg-indigo-200': isToday(date) ==
                                                            false && isSelectedDate(date) == false,
                                                        'bg-indigo-500 text-white hover:bg-opacity-75': isSelectedDate(
                                                            date) == true
                                                    }">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>


                </div>

                <div>
                    @if ($page->status == 'unpublish')
                        <button class="flex gap-x-2 justify-end items-center text-md"
                            wire:click="update('{{ $page->id }}','true')">
                            <x-tabler-send class="icon-lg" />
                            {{ __('Publish') }}
                        </button>
                    @endif
                    @if ($page->status != 'unpublish')
                        <button type="button" wire:click="statusPage({{ $page->id }}, 'unpublish')"
                            class="flex gap-x-2 justify-end items-center text-md bg-gray-100 border-gray-300 text-gray-900  hover:border-blue-500 cursor-pointer">
                            zurück zum Entwurf
                        </button>
                    @endif

                </div>
                <span><strong class="text-gray-600 mt-2">Autor:</strong> Max Mustermann</br></span>
                <strong class="text-gray-600">Seite Template: </strong>
                <select wire:model="page.layout" name="layout">
                    <option value="NULL">Page</option>
                    <option value="is_front_page">Front Page</option>
                    <option value="is_404">404 Page</option>
                </select>

                <strong class="text-gray-600">SEO:</strong>
                <x-kompass::form.textarea wire:model.defer="page.meta_description" id="name" name="title"
                    label="Description" type="text" class="block w-full h-[20rem]" />
                {{-- Thumbnails
                <img src="{{ $page->thumbnails }}" alt=""> --}}
                {{-- <pre>
                {{ $page->content }}
                {{ $page->layout }}
            </pre> --}}

            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <x-kompass::action-message class="" on="status" />
    <x-kompass::modal data="FormDelete" />

    <div class="border-b border-gray-200  py-5 grid-3-2 items-center">

        <div class="relative flex items-center">

                <div class=" flex-auto">
                    <span class="text-gray-400 text-sm">{{ __('Page title') }}</span>


                    <div x-data="click_to_edit()">
                        <a @click.prevent @click="toggleEditingState" x-show="!isEditing" class="flex items-center"
                            class="select-none cursor-pointer">
                            <h5>{{ $page->title }} </h5><span>
                                <x-tabler-edit
                                    class="cursor-pointer stroke-current h-8 w-8 text-gray-400 hover:text-blue-500" />
                            </span>
                        </a>

                        <input type="text" class="focus:outline-none focus:shadow-outline leading-normal"
                            wire:model="page.title" x-show="isEditing" @click.away="toggleEditingState"
                            @keydown.enter="disableEditing" @keydown.window.escape="disableEditing" x-ref="input">
                    </div>
                    <div class="col-span-6">

                    </div>
                    @if ($page->layout == 'is_front_page' || $page->layout == 'is_front_page')
                        <strong class="text-gray-400 text-xs">Permalink: </strong><a
                            class="text-gray-400 text-xs mt-4" href="{{ url('/') }}" target="_blank"
                            rel="noopener noreferrer">{{ url('/') }}</a>
                    @else
                        <strong class="text-gray-400 text-xs">Permalink: </strong><a
                            class="text-gray-400 text-xs mt-4" href="{{ url('/' . $page->slug) }}" target="_blank"
                            rel="noopener noreferrer">{{ url('/' . $page->slug) }}</a>
                    @endif
                </div>


        </div>
        <div class="flex gap-4 justify-end items-center">



            <span x-data="{ open: false }" class="relative transition-all flex gap-4">
                @if ($page->status != 'unpublish')
                    <span
                        class="flex gap-x-2 justify-end items-center text-md  text-gray-900">

                        <span class="relative flex h-3 w-3">
                            <span
                                class="animate-[ping_3s_ease-in-out_infinite] absolute inline-flex h-full w-full rounded-full bg-teal-500 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                        </span>
                        Live
                    </span>
                @endif
                {{-- 
                wire:click="statusPage({{ $page->id }}, 'unpublish')" --}}



                @if ($page->status == 'unpublish')
                    <span class="flex gap-x-2 justify-end items-center text-md border-gray-300 text-gray-900 mx-2">

                        <span class="relative flex h-3 w-3">

                            <span class="relative inline-flex rounded-full h-3 w-3 bg-gray-500"></span>
                        </span>
                        Entwurf
                    </span>
                @endif

                <button class="flex gap-x-2 justify-end items-center text-md"
                    wire:click="update('{{ $page->id }}')">
                    <x-tabler-device-floppy class="icon-lg" />
                    {{ __('Save') }}
                </button>


                <button x-data="{ open: @entangle('FormAdjustments') }"
                    class="flex gap-x-2 justify-end items-center text-md bg-violet-600 border-violet-600"
                    @click="open = true">
                    <x-tabler-adjustments class="icon-lg" />

                </button>

            </span>







        </div>

    </div>
    <div class="">

        <div class="ordre-1">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div wire:sortable="updateBlocksOrder" wire:sortable-group="updateItemsOrder"
                wire:sortable-group.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }"
                wire:sortable.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }"
                class="py-5  ">

                <span class="text-gray-400 text-sm block">Block Builder</span>

                @forelse ($blocks as $itemblocks)
                    <x-kompass::blocksgroup :itemblocks="$itemblocks" :fields="$fields" :page="$page" :class="'itemblock border-blue-400 shadow border-r-4 mt-5'" />

                @empty
                    <div
                        class="grid place-content-center border-2 border-dashed border-gray-300 rounded-2xl h-60 text-gray-400">
                        {{ __('Click "Add" to create the layout') }}</div>
                @endforelse
                <div class="flex justify-end my-6">
                    <button wire:click="selectItem({{ $page->id }}, 'addBlock')">{{ __('Add') }}</button>
                </div>


            </div>

        </div>
    </div>


    <div x-cloak x-data="{ open: @entangle('FormMedia'), ids: @js($getIdField) }" id="FormMedia">
        <x-kompass::offcanvas class="text-gray-500 p-4 m-4">
            <x-slot name="body">
                @livewire('medialibrary', ['fieldId' => $getIdField])
            </x-slot>
        </x-kompass::offcanvas>
    </div>




    <div x-cloak x-data="{ open: @entangle('FormBlocks') }">
        <x-kompass::offcanvas :w="'w-2/4'">
            <x-slot name="body">
                <div class="grid grid-cols-4">
                    @foreach ($blocktemplates as $itemblock)
                        <div class="bg-gray-300 rounded-lg p-2 m-2 cursor-pointer"
                            wire:click.defer="addBlock({{ $page['id'] }},{{ $itemblock['id'] }},'{{ $itemblock['name'] }}','{{ $itemblock['slug'] }}',{{ $itemblock['grid'] }})">
                            @if ($itemblock['icon_img_path'])
                                <img class=" w-full border-gray-200 border-solid border-2 rounded object-cover"
                                    src="{{ asset('storage/' . $itemblock['icon_img_path']) }}" alt="">
                            @endif
                            <span class="text-xs block mt-2">{{ $itemblock['name'] }}</span>
                        </div>
                    @endforeach

                    <div class="bg-gray-300 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click.defer="addBlock({{ $page['id'] }},'','Group','group','1','group')">
                        <img src="{{ kompass_asset('icons-blocks/group.png') }}" alt="">
                        <span class="text-xs block mt-2">Group</span>
                    </div>
                    <div class="bg-gray-300 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click.defer="addBlock({{ $page['id'] }},'','Gallery','gallery','1','gallery')">
                        <img class="rounded" src="{{ kompass_asset('icons-blocks/gallery.png') }}" alt="">
                        <span class="text-xs block mt-2">Gallery</span>
                    </div>
                    <div class="bg-gray-300 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click.defer="addBlock({{ $page['id'] }},'','Tables','tables','1','tables')">
                        <img class="rounded" src="{{ kompass_asset('icons-blocks/gallery.png') }}" alt="">
                        <span class="text-xs block mt-2">Tabellen</span>
                    </div>
                </div>
            </x-slot>
        </x-kompass::offcanvas>
    </div>
</div>


@push('scripts')
    <script>
        const MONTH_NAMES = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ];
        const MONTH_SHORT_NAMES = [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ];
        const DAYS = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

        function app() {
            return {
                showDatepicker: false,
                datepickerValue: "",
                // selectedDate: {{ '2021-02-04' }},
                dateFormat: "DD-MM-YYYY",
                month: "",
                year: "",
                no_of_days: [],
                blankdays: [],
                initDate() {
                    let today;
                    if (this.selectedDate) {
                        today = new Date(Date.parse(this.selectedDate));
                    } else {
                        today = new Date();
                    }
                    this.month = today.getMonth();
                    this.year = today.getFullYear();
                    this.datepickerValue = this.formatDateForDisplay(
                        today
                    );
                },
                formatDateForDisplay(date) {
                    let formattedDay = DAYS[date.getDay()];
                    let formattedDate = ("0" + date.getDate()).slice(
                        -2
                    ); // appends 0 (zero) in single digit date
                    let formattedMonth = MONTH_NAMES[date.getMonth()];
                    let formattedMonthShortName =
                        MONTH_SHORT_NAMES[date.getMonth()];
                    let formattedMonthInNumber = (
                        "0" +
                        (parseInt(date.getMonth()) + 1)
                    ).slice(-2);
                    let formattedYear = date.getFullYear();
                    if (this.dateFormat === "DD-MM-YYYY") {
                        return `${formattedDate}-${formattedMonthInNumber}-${formattedYear}`; // 02-04-2021
                    }
                    if (this.dateFormat === "YYYY-MM-DD") {
                        return `${formattedYear}-${formattedMonthInNumber}-${formattedDate}`; // 2021-04-02
                    }
                    if (this.dateFormat === "D d M, Y") {
                        return `${formattedDay} ${formattedDate} ${formattedMonthShortName} ${formattedYear}`; // Tue 02 Mar 2021
                    }
                    return `${formattedDay} ${formattedDate} ${formattedMonth} ${formattedYear}`;
                },
                isSelectedDate(date) {
                    const d = new Date(this.year, this.month, date);
                    return this.datepickerValue ===
                        this.formatDateForDisplay(d) ?
                        true :
                        false;
                },
                isToday(date) {
                    const today = new Date();
                    const d = new Date(this.year, this.month, date);
                    return today.toDateString() === d.toDateString() ?
                        true :
                        false;
                },
                getDateValue(date) {
                    let selectedDate = new Date(
                        this.year,
                        this.month,
                        date
                    );
                    this.datepickerValue = this.formatDateForDisplay(
                        selectedDate
                    );
                    // this.$refs.date.value = selectedDate.getFullYear() + "-" + ('0' + formattedMonthInNumber).slice(-2) + "-" + ('0' + selectedDate.getDate()).slice(-2);
                    this.isSelectedDate(date);
                    this.showDatepicker = false;
                },
                getNoOfDays() {
                    let daysInMonth = new Date(
                        this.year,
                        this.month + 1,
                        0
                    ).getDate();
                    // find where to start calendar day of week
                    let dayOfWeek = new Date(
                        this.year,
                        this.month
                    ).getDay();
                    let blankdaysArray = [];
                    for (var i = 1; i <= dayOfWeek; i++) {
                        blankdaysArray.push(i);
                    }
                    let daysArray = [];
                    for (var i = 1; i <= daysInMonth; i++) {
                        daysArray.push(i);
                    }
                    this.blankdays = blankdaysArray;
                    this.no_of_days = daysArray;
                },
            };
        }
    </script>
@endpush
