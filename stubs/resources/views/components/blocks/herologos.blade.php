@props([
    'item' => '',
])


@if ($item->getMeta('css-classname') == 'hero')


    <div class="hero-logos fullpage z-40">

        <div class="flex md:grid grid-cols-6 gap-8 py-7 md:py-0 ">
            <div class="col-start-1 md:col-end-4 col-end-13">
                <h4>Freitag, 6. September 2024</h4>
                <h4>Galerie Herrenhausen</h4>

            </div>
            <div class="md:col-start-4 md:col-end-6  justify-self-end">
                Eine Veranstaltung von:
                <img class=" w-[18rem]" src="{{ asset('img/logos/rotary.png') }}" />
            </div>
            <div class="md:col-end-7">
                Medienpartner:
                <img src="{{ asset('img/logos/radio21.png') }}" />
            </div>


        </div>
        Mit freundlicher Unterstützung von:
        <div class="grid grid-cols-3 md:grid-cols-6 gap-2 bg-white p-1 mb-1">


                <div>
                    <img src="{{ asset('img/logos/bundb.png') }}" alt="bundb" />
                </div>
                <div>
                    <img src="{{ asset('img/logos/brandi_Logo.png') }}" alt="brandi" />
                </div>
                <div>
                    <img src="{{ asset('img/logos/eichels-Event-GmbH2.png') }}" alt="eichels-Event-GmbH" />
                </div>
                <div>
                    <img src="{{ asset('img/logos/rossmann.png') }}" alt="rossmann" />
                </div>
                <div>
                    <img src="{{ asset('img/logos/getec.png') }}" alt="getec" />
                </div>
                <div>
                    <img src="{{ asset('img/logos/kind.png') }}" alt="getec" />
                </div>

        </div>

 <!-- Logo Carousel animation -->
 <div
 x-data="{}"
 x-init="$nextTick(() => {
     let ul = $refs.logos;
     ul.insertAdjacentHTML('afterend', ul.outerHTML);
     ul.nextSibling.setAttribute('aria-hidden', 'true');
 })"
 class="w-full bg-white inline-flex flex-nowrap overflow-hidden "
>
 <ul x-ref="logos" class="flex items-center justify-center md:justify-start [&_li]:mx-2 [&_img]:max-w-44 animate-infinite-scroll">
     <li>
        <img src="{{ asset('img/logos/schlosskueche.png') }}" alt="schlosskueche" />
     </li>
     <li>
        <img src="{{ asset('img/logos/dieckhoff.png') }}" alt="dieckhoff" />
     </li>
     <li>
        <img src="{{ asset('img/logos/schoepf.png') }}" alt="schoepf" />
     </li>
     <li>
        <img src="{{ asset('img/logos/hv.png') }}" alt="hv" />
     </li>
     <li>
        <img src="{{ asset('img/logos/selatec.png') }}" alt="selatec" />
     </li>
     <li>
        <img src="{{ asset('img/logos/hoffmannv2.png') }}" alt="hoffmann" />
     </li>
     <li>
        <img src="{{ asset('img/logos/zag2.png') }}" alt="zag" />
     </li>
     <li>
        <img src="{{ asset('img/logos/ini.png') }}" alt="ini" />
     </li>
     <li>
        <img src="{{ asset('img/logos/HRE-Logo.png') }}" alt="ini" />
     </li>
 </ul>
</div>
<!-- End: Logo Carousel animation -->


        {{-- <div class="flex overflow-hidden grid-cols-4 md:grid-cols-8 gap-2 bg-white p-1 mb-1 [mask-image:_linear-gradient(to_right,transparent_0,_black_128px,_black_calc(100%-128px),transparent_100%)]">

            <div x-ref="logos" class="flex items-center justify-center md:justify-start  [&_img]:max-w-none animate-infinite-scroll">
            <div>
                <img src="{{ asset('img/logos/schlosskueche.png') }}" alt="schlosskueche" />
            </div>
            <div>
                <img src="{{ asset('img/logos/dieckhoff.png') }}" alt="dieckhoff" />
            </div>
            <div>
                <img src="{{ asset('img/logos/schoepf.png') }}" alt="schoepf" />
            </div>
            <div>
                <img src="{{ asset('img/logos/hv.png') }}" alt="hv" />
            </div>
            <div>
                <img src="{{ asset('img/logos/selatec.png') }}" alt="selatec" />
            </div>
            <div>
                <img src="{{ asset('img/logos/hoffmannv2.png') }}" alt="hoffmann" />
            </div>
            <div>
                <img src="{{ asset('img/logos/zag2.png') }}" alt="zag" />
            </div>
            <div>
                <img src="{{ asset('img/logos/ini.png') }}" alt="ini" />
            </div>

</div>

    </div> --}}


    </div>




@endif
