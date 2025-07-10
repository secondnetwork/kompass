@extends('kompass::admin.layouts.app')

@section('content')
<h1>cd</h1>

   

<h1 class="text-5xl">Heading 1</h1>
<h2 class="text-4xl">Heading 2</h2>
<h3 class="text-3xl">Heading 3</h3>
<h4 class="text-2xl">Heading 4</h4>
<h5 class="text-xl">Heading 5</h5>
<h6 class="text-lg">Heading 6</h6>
<p class="">This is a paragraph</p>

<h2 class="text-gray-900 text-4xl font-extrabold md:text-5xl lg:text-6xl">
  This is <span class="text-indigo-600">heading</span>
  </h2>
  <h2 class="text-gray-900 text-4xl font-extrabold md:text-5xl lg:text-6xl">
  This is <span class="px-2 text-white bg-indigo-600 rounded">heading</span>
  </h2>
  <h2 class="text-gray-900 text-4xl font-extrabold md:text-5xl lg:text-6xl">
  This is text <span class="text-transparent bg-clip-text bg-gradient-to-r to-indigo-600 from-violet-400">gradient</span>
  </h2>
  <h2 class="text-gray-900 text-4xl font-extrabold md:text-5xl lg:text-6xl">
  This is text <span class="underline underline-offset-3 decoration-8 decoration-indigo-600 ">underline</span>
  </h2>


<div class="logo"><img class="h-[6rem]" src="{{ kompass_asset('kompass_logo.svg')}}" alt=""></div>

{{-- <button type="button" class="hs-dark-mode-active:hidden block hs-dark-mode font-medium text-gray-800 rounded-full hover:bg-gray-200 focus:outline-none focus:bg-gray-200 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" data-hs-theme-click-value="dark">
    <span class="group inline-flex shrink-0 justify-center items-center size-9">
      <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
      </svg>
    </span>
  </button>
  <button type="button" class="hs-dark-mode-active:block hidden hs-dark-mode font-medium text-gray-800 rounded-full hover:bg-gray-200 focus:outline-none focus:bg-gray-200 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" data-hs-theme-click-value="light">
    <span class="group inline-flex shrink-0 justify-center items-center size-9">
      <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="4"></circle>
        <path d="M12 2v2"></path>
        <path d="M12 20v2"></path>
        <path d="m4.93 4.93 1.41 1.41"></path>
        <path d="m17.66 17.66 1.41 1.41"></path>
        <path d="M2 12h2"></path>
        <path d="M20 12h2"></path>
        <path d="m6.34 17.66-1.41 1.41"></path>
        <path d="m19.07 4.93-1.41 1.41"></path>
      </svg>
    </span>
  </button> --}}



  <input data-hs-theme-switch="" type="checkbox" value="synthwave" class="toggle theme-controller" />

<div class="grid grid-cols-3 gap-8 pt-8">
<a href="/admin/eventdata" class="flex gap-2 items-center bg-gray-100 rounded-2xl shadow col-span-3 md:col-span-1">
  <x-tabler-checkbox class="w-16 h-16 stroke-[1.5] stroke-green-500 bg-green-100 border-2 border-green-500  rounded-xl p-2"/>
    <div class=" font-semibold text-5xl text-green-500">32
        {{-- @php
            $data = DB::table('contacts')->count();
        @endphp
        {{ $data ?? '' }} --}}

    </div><span class="font-semibold">Angemeldet</span>
</a>


{{-- @php
$datawerbung = DB::table('contacts')->whereNotNull('werbung')->count();
@endphp --}}



<a href="/admin/eventdata" class="flex gap-2 items-center  bg-gray-100 rounded-2xl shadow col-span-3 md:col-span-1">
  <x-tabler-ticket-off class="w-16 h-16 stroke-[1.5] stroke-red-500 bg-red-100 border-2 border-red-500 rounded-xl p-2"/>
    <div class=" font-semibold text-5xl text-red-500">
        {{-- @php
            $data = DB::table('contacts')->where('status','abgesagt')->count();
        @endphp
        {{ $data ?? '' }} --}}
2
    </div><span class="font-semibold">Abgesagt</span>
</a>


{{-- @if ($datawerbung) --}}
<a href="/admin/eventdata" class="flex gap-2 items-center bg-gray-100 rounded-2xl shadow col-span-3  md:col-span-1">
  <x-tabler-mail-forward class="w-16 h-16 stroke-[1.5] stroke-cyan-600 bg-cyan-100  border-2 border-cyan-600 rounded-xl p-2"/>
    <div class=" font-semibold text-5xl text-cyan-600">

        {{-- {{ $datawerbung ?? '' }} --}}
2
    </div><span class="font-semibold">Werbung</span>
</a>
{{-- @endif --}}

{{-- @php
$databegleitung = DB::table('contacts')->whereNull('status')->WhereNotNull('begleitung')->count();
@endphp
@if ($databegleitung) --}}
<a href="/admin/eventdata" class="flex gap-2 items-center bg-gray-100 rounded-2xl shadow col-span-3">
  <x-tabler-user-plus class="w-16 h-16 stroke-[1.5] stroke-purple-600 bg-purple-100  border-2 border-purple-600 rounded-xl p-2"/>
    <div class=" font-semibold text-5xl text-purple-600">

        {{ $databegleitung ?? '' }}
8
    </div><span class="font-semibold">Mit Begleitung</span>
</a>
{{-- @endif --}}
</div>

<x-kompass::daisyui.heading class="mb-2">sdfsfsdfdsf</x-kompass::daisyui.heading>
<x-kompass::daisyui.heading type="sub">sdffsdfsdfsdf</x-kompass::daisyui.heading>
<x-kompass::daisyui.separator variant="neutral" />

<x-kompass::daisyui.modal name="naff">
  nsh


</x-kompass::daisyui.modal>

<label for="modal_naff" class="btn">open modal</label>
<div class="stats shadow w-full my-16">
  <div class="stat">
    <div class="stat-figure text-primary">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        class="inline-block h-8 w-8 stroke-current">
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
      </svg>
    </div>
    <div class="stat-title">Total Likes</div>
    <div class="stat-value text-primary">25.6K</div>
    <div class="stat-desc">21% more than last month</div>
  </div>

  <div class="stat">
    <div class="stat-figure text-secondary">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        class="inline-block h-8 w-8 stroke-current">
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M13 10V3L4 14h7v7l9-11h-7z"></path>
      </svg>
    </div>
    <div class="stat-title">Page Views</div>
    <div class="stat-value text-secondary">2.6M</div>
    <div class="stat-desc">21% more than last month</div>
  </div>

  <div class="stat">
    <div class="stat-figure text-secondary">
      <div class="avatar online">
        <div class="w-16 rounded-full">
          <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
        </div>
      </div>
    </div>
    <div class="stat-value">86%</div>
    <div class="stat-title">Tasks done</div>
    <div class="stat-desc text-secondary">31 tasks remaining</div>
  </div>
</div>
<div class="flex items-center gap-2 py-6">
  <x-kompass::daisyui.button variant="primary" type="submit" class="btn-outline">{{ __('Email password reset link') }}</x-kompass::daisyui.button>


<button class="btn btn-outline">Default</button>
<button class="btn btn-outline btn-primary">Primary</button>
<button class="btn btn-outline btn-secondary btn-active">Secondary</button>
<button class="btn btn-outline btn-accent">Accent</button>
<button class="btn btn-outline btn-info">Info</button>
<button class="btn btn-outline btn-success">Success</button>
<button class="btn btn-outline btn-warning">Warning</button>
<button class="btn btn-outline btn-error">Error</button>
</div>
<div class="flex items-center gap-2 py-6">
<button class="btn">Default</button>
<button class="btn btn-primary">Primary</button>
<button class="btn btn-secondary">Secondary</button>
<button class="btn btn-accent">Accent</button>
<button class="btn btn-info">Info</button>
<button class="btn btn-success">Success</button>
<button class="btn btn-warning">Warning</button>
<button class="btn btn-error">Error</button>

<button class="btn" disabled="disabled">Disabled using attribute</button>
<button class="btn btn-disabled" tabindex="-1" role="button" aria-disabled="true">
  Disabled using class name
</button>
</div>

<div class="card bg-neutral text-neutral-content w-96">
  <div class="card-body items-center text-center">
    <h2 class="card-title">Cookies!</h2>
    <p>We are using cookies for no reason.</p>
    <div class="card-actions justify-end">
      <button class="btn btn-primary">Accept</button>
      <button class="btn btn-ghost">Deny</button>
    </div>
  </div>
</div>



<div class="badge badge-primary">Primary</div>
<div class="badge badge-secondary">Secondary</div>
<div class="badge badge-accent">Accent</div>
<div class="badge badge-neutral">Neutral</div>
<div class="badge badge-info">Info</div>
<div class="badge badge-success">Success</div>
<div class="badge badge-warning">Warning</div>
<div class="badge badge-error">Error</div>


  <div class="card card-border border-base-300 card-sm overflow-hidden"><div class="card-body gap-4"><div class="border-b-base-300 grid grid-cols-7 border-b border-dashed pb-3"><div class="rounded-field flex flex-col items-center px-2 py-1"><span class="text-sm font-semibold">12</span> <span class="text-[10px] font-semibold opacity-50">M</span></div> <div class="rounded-field flex flex-col items-center px-2 py-1"><span class="text-sm font-semibold">13</span> <span class="text-[10px] font-semibold opacity-50">T</span></div> <div class="rounded-field bg-primary text-primary-content flex flex-col items-center px-2 py-1"><span class="text-sm font-semibold">14</span> <span class="text-[10px] font-semibold opacity-50">W</span></div> <div class="rounded-field flex flex-col items-center px-2 py-1"><span class="text-sm font-semibold">15</span> <span class="text-[10px] font-semibold opacity-50">T</span></div> <div class="rounded-field flex flex-col items-center px-2 py-1"><span class="text-sm font-semibold">16</span> <span class="text-[10px] font-semibold opacity-50">F</span></div> <div class="rounded-field flex flex-col items-center px-2 py-1"><span class="text-sm font-semibold">17</span> <span class="text-[10px] font-semibold opacity-50">S</span></div> <div class="rounded-field flex flex-col items-center px-2 py-1"><span class="text-sm font-semibold">18</span> <span class="text-[10px] font-semibold opacity-50">S</span></div></div> <div><label class="input input-sm input-border flex w-auto items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4"><path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd"></path></svg> <input type="text" placeholder="Search for events"></label></div> <div class="flex flex-col gap-2"><label class="flex cursor-pointer items-center gap-2"><input type="checkbox" class="toggle toggle-sm toggle-primary" checked=""> <span class="text-xs">Show all day events</span></label></div></div> <div class="bg-base-300"><div class="flex items-center gap-2 p-4"><div class="grow"><div class="text-sm font-medium">Team Sync Meeting</div> <div class="text-xs opacity-60">Weekly product review with design and development teams</div></div> <div class="shrink-0"><span class="badge badge-sm badge-neutral">1h</span></div></div></div></div>

<div class="lg:border-base-content/5 mb-16 rounded-2xl lg:border lg:p-4"><div class="border-base-content/10 overflow-hidden rounded-lg border-[0.5px]"><div class="grid grid-cols-1"><div class="bg-primary text-primary-content group border-base-content/10 grid h-24 place-content-end gap-1 p-6 text-end"><div class="font-title translate-y-1 text-sm font-semibold tracking-widest opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">Primary</div> <div class="font-mono text-[0.625rem] tracking-widest tabular-nums">oklch(54% 0.245 262.881)</div></div><div class="bg-secondary text-secondary-content group border-base-content/10 grid h-24 place-content-end gap-1 p-6 text-end"><div class="font-title translate-y-1 text-sm font-semibold tracking-widest opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">Secondary</div> <div class="font-mono text-[0.625rem] tracking-widest tabular-nums">oklch(86% 0.127 207.078)</div></div><div class="bg-accent text-accent-content group border-base-content/10 grid h-24 place-content-end gap-1 p-6 text-end"><div class="font-title translate-y-1 text-sm font-semibold tracking-widest opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">Accent</div> <div class="font-mono text-[0.625rem] tracking-widest tabular-nums">oklch(75% 0.183 55.934)</div></div><div class="bg-neutral text-neutral-content group border-base-content/10 grid h-24 place-content-end gap-1 p-6 text-end"><div class="font-title translate-y-1 text-sm font-semibold tracking-widest opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">Neutral</div> <div class="font-mono text-[0.625rem] tracking-widest tabular-nums">oklch(55% 0.016 285.938)</div></div></div></div> <div class="border-base-content/10 mt-4 overflow-hidden rounded-lg border-[0.5px]"><div class="grid xl:grid-cols-3"><div class="bg-base-100 text-base-content group border-base-content/10 grid h-36 place-content-end gap-1 p-6 text-end"><div class="font-title translate-y-1 text-sm font-semibold tracking-widest opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">Base 100</div> <div class="truncate font-mono text-[0.625rem] tabular-nums">oklch(98% 0.002 247.839)</div></div><div class="bg-base-200 text-base-content group border-base-content/10 grid h-36 place-content-end gap-1 p-6 text-end"><div class="font-title translate-y-1 text-sm font-semibold tracking-widest opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">Base 200</div> <div class="truncate font-mono text-[0.625rem] tabular-nums">oklch(100% 0 0)</div></div><div class="bg-base-300 text-base-content group border-base-content/10 grid h-36 place-content-end gap-1 p-6 text-end"><div class="font-title translate-y-1 text-sm font-semibold tracking-widest opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">Base 300</div> <div class="truncate font-mono text-[0.625rem] tabular-nums">oklch(96% 0.007 247.896)</div></div></div></div> <div class="border-base-content/10 mt-4 overflow-hidden rounded-lg border-[0.5px]"><div class="grid xl:grid-cols-4"><div class="bg-info text-info-content group border-base-content/10 grid h-24 place-content-end gap-1 p-6 text-end"><div class="font-title translate-y-1 text-sm font-semibold tracking-widest opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">Info</div> <div class="truncate font-mono text-[0.625rem] tabular-nums">oklch(58% 0.158 241.966)</div></div><div class="bg-success text-success-content group border-base-content/10 grid h-24 place-content-end gap-1 p-6 text-end"><div class="font-title translate-y-1 text-sm font-semibold tracking-widest opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">Success</div> <div class="truncate font-mono text-[0.625rem] tabular-nums">oklch(64% 0.2 131.684)</div></div><div class="bg-warning text-warning-content group border-base-content/10 grid h-24 place-content-end gap-1 p-6 text-end"><div class="font-title translate-y-1 text-sm font-semibold tracking-widest opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">Warning</div> <div class="truncate font-mono text-[0.625rem] tabular-nums">oklch(64% 0.2 131.684)</div></div><div class="bg-error text-error-content group border-base-content/10 grid h-24 place-content-end gap-1 p-6 text-end"><div class="font-title translate-y-1 text-sm font-semibold tracking-widest opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">Error</div> <div class="truncate font-mono text-[0.625rem] tabular-nums">oklch(64% 0.2 131.684)</div></div></div></div></div>

@endsection
