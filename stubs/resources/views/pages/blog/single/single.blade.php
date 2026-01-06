@use('Illuminate\Support\Facades\Storage')
@use('Carbon\Carbon')
@use('Illuminate\Support\Str')

<div>
    @if (!empty($post->slug))
        @section('slug', $post->slug)
        @seo(['title' => $post->title . ' | ' . setting('global.webtitle' ?? 'Kompass')])
        @php
            seo()
                ->description($post->meta_description ?? setting('global.description' ?? ''))
                // ->locale(str_replace('_', '-', app()->getLocale()))
                // ->twitter()
                // ->tag('og:image', asset(setting('global.ogimage_src')))
                // ->twitter();
        @endphp
    @endif


    <section class="popout">
        <!-- Blog Article -->
        <div class="">
            <div class="">
                <!-- Avatar Media -->
                <div class="flex justify-between items-center mb-6">
                    <div class="flex w-full sm:items-center gap-x-5 sm:gap-x-3">
                        <div class="shrink-0">
                            <img class="size-12 rounded-full"
                                src="https://images.unsplash.com/photo-1669837401587-f9a4cfe3126e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80"
                                alt="Avatar">
                        </div>

                        <div class="grow">
                            <div class="flex justify-between items-center gap-x-2">
                                <div>
                                    <!-- Tooltip -->
                                    <div class="hs-tooltip [--trigger:hover] [--placement:bottom] inline-block">
                                        <div class="hs-tooltip-toggle sm:mb-1 block text-start cursor-pointer">
                                            <span class="font-semibold text-gray-800">
                                                Leyla Ludic
                                            </span>

                                        </div>
                                    </div>
                                    <!-- End Tooltip -->

                                    <ul class="text-xs text-gray-500">
                                        <li
                                            class="inline-block relative pe-6 last:pe-0 last-of-type:before:hidden before:absolute before:top-1/2 before:end-2 before:-translate-y-1/2 before:size-1 before:bg-gray-300 before:rounded-full">
                                            {{ Carbon::parse($post->updated_at)->format('d. F Y') }}
                                        </li>
               
                                    </ul>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Avatar Media -->

                <!-- Content -->
                <div class="space-y-5 md:space-y-8">
                    <div class="space-y-3">
                        <h2 class="text-2xl font-bold md:text-3xl">{{ $post->title }}</h2>

                        <div class="relative rounded-xl overflow-hidden my-6">
                             <x-image :id="$post->thumbnails" class="object-cover w-full h-full rounded-lg shadow-md aspect-video" />
          
                         

                            <span
                                class="absolute top-0 end-0 rounded-se-xl rounded-es-xl text-xs font-medium bg-gray-800 text-white py-1.5 px-3">
                                Sponsored
                            </span>
                        </div>

                        @if ($this->blocks)
                        @foreach ($this->blocks as $key => $item)
                            @php
                                // Der Name der Komponente, z.B. "blocks.hero"
                                $componentName = 'blocks.' . $item->type;

                                // Der Pfad zur View, z.B. "components.blocks.hero"
                                $viewName = 'components.' . $componentName;
                            @endphp

                            @if (view()->exists($viewName))
                                {{-- Komponente existiert -> Rendern --}}
                                <section
                                    class="{{ $item->getMeta('layout') ?? 'fullpage' }} {{ $item->getMeta('css-classname') }}"
                                    id="{{ $item->getMeta('id-anchor') }}">
                                    <x-dynamic-component :component="$componentName" :item="$item" />
                                </section>
                            @elseif (app()->hasDebugModeEnabled())
                                {{-- Komponente fehlt & wir sind im Dev-Modus -> Fehler anzeigen --}}
                                <section
                                    class="fullpage border border-dashed border-red-500 bg-red-50 p-4 text-red-600 rounded">
                                    <strong>Entwickler-Info:</strong><br>
                                    Die Komponente <code>&lt;x-{{ $componentName }} /&gt;</code> wurde nicht
                                    gefunden.<br>
                                </section>
                            @else
                                {{-- Produktion -> Einfach nichts anzeigen (Fallback) --}}
                                <!-- Block {{ $item->type }} konnte nicht geladen werden -->
                            @endif
                        @endforeach
                        @endif
        

                    </div>

                    <!-- End Content -->
                </div>
            </div>
            <!-- End Blog Article -->

    </section>

</div>
