@props([
    'videourl' => '',
    'poster' => '',
])
{{-- {{ $poster }} --}}
<div x-data="{
    sources: {
        mp4: '{{ $videourl }}',
        {{-- webm: 'https://cdn.devdojo.com/pines/videos/coast.webm',
        ogg: 'https://cdn.devdojo.com/pines/videos/coast.ogg' --}}
    },
    playing: false,
    controls: true,
    muted: false,
    muteForced: false,
    fullscreen: false,
    ended: false,
    mouseleave: false,
    autoHideControlsDelay: 3000,
    controlsHideTimeout: null,
    poster: '{{ $poster ?? null }}',
    videoDuration: 0,
    timeDurationString: '00:00',
    timeElapsedString: '00:00',
    showTime: false,
    volume: 1,
    volumeBeforeMute: 1,
    videoPlayerReady: false,
    timelineSeek(e) {
        time = this.formatTime(Math.round(e.target.value));
        this.timeElapsedString = `${time.minutes}:${time.seconds}`;
    },
    metaDataLoaded(event) {
        this.videoDuration = event.target.duration;
        this.$refs.videoProgress.setAttribute('max', this.videoDuration);

        time = this.formatTime(Math.round(this.videoDuration));
        this.timeDurationString = `${time.minutes}:${time.seconds}`;
        this.showTime = true;
        this.videoPlayerReady = true;
    },
    togglePlay(e) {
        if (this.$refs.player.paused || this.$refs.player.ended) {
            this.playing = true;
            this.$refs.player.play();
        } else {
            this.$refs.player.pause();
            this.playing = false;
        }
    },
    toggleMute(){
        this.muted = !this.muted;
        this.$refs.player.muted = this.muted;
        if(this.muted){
            this.volumeBeforeMute = this.volume;
            this.volume = 0;
        } else {
            this.volume = this.volumeBeforeMute;
        }
    },
    timeUpdatedInterval() {
        if (!this.$refs.videoProgress.getAttribute('max'))
            this.$refs.videoProgress.setAttribute('max', $refs.player.duration);
            this.$refs.videoProgress.value = this.$refs.player.currentTime;
            time = this.formatTime(Math.round(this.$refs.player.currentTime));
            this.timeElapsedString = `${time.minutes}:${time.seconds}`;
    },
    updateVolume(e) {
        this.volume = e.target.value;
        this.$refs.player.volume = this.volume;
        if(this.volume == 0){
            this.muted = true;
        }

        if(this.muted && this.volume > 0){
            this.muted = false;
        }
    },
    timelineClicked(e) {
        rect = this.$refs.videoProgress.getBoundingClientRect();
        pos = (e.pageX - rect.left) / this.$refs.videoProgress.offsetWidth;
        this.$refs.player.currentTime = pos * this.$refs.player.duration;
    },
    handleFullscreen() {
        if (document.fullscreenElement !== null) {
            // The document is in fullscreen mode
            document.exitFullscreen();
        } else {
            // The document is not in fullscreen mode
            this.$refs.videoContainer.requestFullscreen();
        }
    },
    mousemoveVideo() {
        if(this.playing){
            this.resetControlsTimeout();
        } else {
            this.controls=true;
            clearTimeout(this.controlsHideTimeout);
        }
    },
    videoEnded() {
        this.ended = true;
        this.playing = false;
        this.$refs.player.currentTime = 0;
    },
    resetControlsTimeout() {
        this.controls = true;
        clearTimeout(this.controlsHideTimeout);
        let that = this;
        this.controlsHideTimeout = setTimeout(function(){
            that.controls=false
        }, this.autoHideControlsDelay);
    },
    formatTime(timeInSeconds) {
        result = new Date(timeInSeconds * 1000).toISOString().substr(11, 8);

        return {
            minutes: result.substr(3, 2),
            seconds: result.substr(6, 2),
        };
    }
}"

x-init="

    $refs.player.load();
    // Hide the default player controls
    $refs.player.controls = false;

    $watch('playing', (value) => {
        if (value) {
            ended = false;
            controlsHideTimeout = setTimeout(() => {
                controls = false;
            }, autoHideControlsDelay);
        } else {
            clearTimeout(controlsHideTimeout);
            controls = true;
        }
    });

    if (!document?.fullscreenEnabled) {
        $refs.fullscreenButton.style.display = 'none';
    }

    document.addEventListener('fullscreenchange', (e) => {
        fullscreen = !!document.fullscreenElement;
    });

"
x-ref="videoContainer"
@mouseleave="mouseleave=true"
@mousemove="mousemoveVideo"
class="relative overflow-hidden rounded-md aspect-video">
<video
    x-ref="player"
    @loadedmetadata="metaDataLoaded"
    @timeupdate="timeUpdatedInterval"
    @ended="videoEnded"
    preload="metadata"
    :poster="poster"
    class="relative z-10 object-cover w-full h-full bg-black"
    crossorigin="anonymous"
    >
    <source :src="sources.mp4" type="video/mp4" />
    {{-- <source :src="sources.webm" type="video/webm" />
    <source :src="sources.ogg" type="video/ogg" /> --}}
</video>
<div x-show="videoPlayerReady" class="absolute inset-0 w-full h-full">
    <div x-ref="videoBackground" @click="togglePlay()" class="absolute inset-0 z-30 flex items-center justify-center w-full h-full  bg-opacity-0 cursor-pointer group">
        <div
            x-show="playing"
            x-transition:enter="transition ease-out duration-1000"
            x-transition:enter-start="scale-50 opacity-100"
            x-transition:enter-end="scale-100 opacity-0"
            class="absolute z-20 flex items-center justify-center w-24 h-24 bg-blue-600 rounded-full opacity-0 bg-opacity-20"
            x-cloak>
            <x-tabler-player-play-filled class="w-10 h-10 translate-x-0.5 text-white" />
        </div>
        <div
            x-show="!playing && !ended"
            x-transition:enter="transition ease-out duration-1000"
            x-transition:enter-start="scale-50 opacity-100"
            x-transition:enter-end="scale-100 opacity-0"
            class="absolute z-20 flex items-center justify-center w-24 h-24 bg-blue-600 rounded-full opacity-0 bg-opacity-20"
            x-cloak>
            <x-tabler-player-pause-filled class="w-10 h-10 text-white" />
        </div>
        <div class="absolute z-10 duration-300 ease-out group-hover:scale-110">
            <button
                x-show="!playing"
                x-transition:enter="transition ease-in delay-200 duration-300"
                x-transition:enter-start="opacity-0 scale-75"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-out duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="flex items-center justify-center w-12 h-12 text-white duration-150 ease-out bg-blue-600 rounded-full cursor-pointer bg-opacity-80" type="button">
                <x-tabler-player-play-filled class="w-5 h-5 translate-x-px" x-cloak />
            </button>
        </div>
    </div>
    <div x-show="controls"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="-translate-y-full"
        class="absolute top-0 left-0 z-20 w-full h-1/4 opacity-20 bg-gradient-to-b from-black to-transparent" x-cloak>
    </div>
    <div x-show="controls"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="absolute bottom-0 left-0 z-20 w-full h-1/4 opacity-20 bg-gradient-to-b from-transparent to-black" x-cloak>
    </div>
    <div x-show="controls"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="absolute bottom-0 left-0 z-20 w-full h-1/2 opacity-20 bg-gradient-to-b from-transparent to-black" x-cloak>
    </div>
    <div x-show="controls"
        @click="resetControlsTimeout"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="absolute bottom-0 left-0 z-40 w-full h-12" x-cloak>
        <ul class="absolute bottom-0 left-0 z-20 flex items-center w-full text-white">
            <li class="inline">
                <button @click="togglePlay()" class="flex items-center justify-center w-10 h-10 duration-150 ease-out opacity-80 hover:opacity-100" type="button">
                    <x-tabler-player-play-filled x-show="!playing" class="w-5 h-5" x-cloak />
                    <x-tabler-player-pause-filled x-show="playing" class="w-5 h-5" x-cloak />
                </button>
            </li>
            <li class="w-full">
                <div class="relative w-full h-4 rounded-full">
                    <input
                        x-ref="videoProgress"
                        @click="timelineClicked"
                        @input="timelineSeek(event)"
                        type="range" min="0" max="100" value="0" step="any"
                        class="w-full h-full appearance-none flex items-center cursor-pointer bg-transparent z-30
                            [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:border-0 [&::-webkit-slider-thumb]:w-2.5 [&::-webkit-slider-thumb]:h-2.5 [&::-webkit-slider-thumb]:appearance-none
                            [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-0 [&::-moz-range-thumb]:w-2.5 [&::-moz-range-thumb]:h-2.5 [&::-moz-range-thumb]:appearance-none
                            [&::-ms-thumb]:bg-white [&::-ms-thumb]:rounded-full [&::-ms-thumb]:border-0 [&::-ms-thumb]:w-2.5 [&::-ms-thumb]:h-2.5 [&::-ms-thumb]:appearance-none
                            [&::-webkit-slider-runnable-track]:bg-white [&::-webkit-slider-runnable-track]:bg-opacity-30 [&::-webkit-slider-runnable-track]:rounded-full [&::-webkit-slider-runnable-track]:overflow-hidden [&::-moz-range-track]:bg-neutral-200 [&::-moz-range-track]:rounded-full [&::-ms-track]:bg-neutral-200 [&::-ms-track]:rounded-full
                            [&::-moz-range-progress]:bg-gray-900 [&::-moz-range-progress]:rounded-full [&::-ms-fill-lower]:bg-gray-900 [&::-ms-fill-lower]:rounded-full [&::-webkit-slider-thumb]:shadow-[-995px_0px_0px_990px_#101827]
                    ">
                </div>
            </li>
            <li x-show="showTime" class="mx-2.5 flex-shrink-0 font-mono text-xs opacity-80 hover:opacity-100">
                <time x-ref="timeElapsed" x-text="timeElapsedString">00:00</time>
                <span> / </span>
                <time x-ref="timeDuration" x-text="timeDurationString">00:00</time>
            </li>
            <li class="flex items-center group">
                <button @click="toggleMute()" type="button" class="flex items-center justify-center w-6 h-10 duration-150 ease-out opacity-80 hover:opacity-100">
                    <x-tabler-volume x-show="!muted" class="w-[18px] h-[18px]" x-cloak />
                    <x-tabler-volume-off x-show="muted" class="w-[18px] h-[18px]" x-cloak />
                </button>
                <div class="relative h-1.5 w-0 mx-0 group-hover:mx-1 rounded-full group-hover:w-12 invisible group-hover:visible w-0 ease-out duration-300">
                <div class="relative h-1.5 w-0 mx-0 group-hover:mx-1 rounded-full group-hover:w-12 invisible group-hover:visible w-0 ease-out duration-300">
                    <input
                        x-ref="volume"
                        @input="updateVolume(event)"
                        type="range" min="0" max="1" :value="volume" step="0.01"
                        class="w-full h-full appearance-none flex items-center cursor-pointer bg-transparent z-30
                            [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:border-0 [&::-webkit-slider-thumb]:w-2 [&::-webkit-slider-thumb]:h-2 [&::-webkit-slider-thumb]:appearance-none
                            [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-0 [&::-moz-range-thumb]:w-2 [&::-moz-range-thumb]:h-2 [&::-moz-range-thumb]:appearance-none
                            [&::-ms-thumb]:bg-white [&::-ms-thumb]:rounded-full [&::-ms-thumb]:border-0 [&::-ms-thumb]:w-2 [&::-ms-thumb]:h-2 [&::-ms-thumb]:appearance-none
                            [&::-webkit-slider-runnable-track]:bg-white [&::-webkit-slider-runnable-track]:bg-opacity-30 [&::-webkit-slider-runnable-track]:rounded-full [&::-webkit-slider-runnable-track]:overflow-hidden [&::-moz-range-track]:bg-neutral-200 [&::-moz-range-track]:rounded-full [&::-ms-track]:bg-neutral-200 [&::-ms-track]:rounded-full
                            [&::-moz-range-progress]:bg-white [&::-moz-range-progress]:bg-opacity-80 [&::-moz-range-progress]:rounded-full [&::-ms-fill-lower]:bg-white [&::-ms-fill-lower]:bg-opacity-80 [&::-ms-fill-lower]:rounded-full [&::-webkit-slider-thumb]:shadow-[-995px_0px_0px_990px_rgba(255,_255,_255,_0.8)]
                    ">
                </div>
            </li>
            <li class="ml-auto">
                <button x-ref="fullscreenButton" @click="handleFullscreen" class="flex items-center justify-center w-10 h-10 duration-150 ease-out scale-90 opacity-80 hover:opacity-100 hover:scale-100" type="button">
                    <x-tabler-maximize class="w-5 h-5" />
                </button>
            </li>
        </ul>
    </div>
</div>
</div>
