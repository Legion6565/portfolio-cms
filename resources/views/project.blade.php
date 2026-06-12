@extends('layouts.app')

@section('content')

<div x-data="lightbox">

    {{-- HERO --}}
    <section class="max-w-7xl mx-auto px-6 pt-20 pb-20">

        <div class="grid lg:grid-cols-12 gap-16 items-end">

            <div class="lg:col-span-7">

                @if($project->project_date)
                    <p class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-8">
                        {{ $project->project_date }}
                    </p>
                @endif

                <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold tracking-tight text-slate-900 leading-none">
                    {{ $project->title }}
                </h1>

                @if($project->short_description)
                    <p class="text-xl md:text-2xl text-slate-600 mt-10 leading-relaxed max-w-2xl">
                        {{ $project->short_description }}
                    </p>
                @endif

            </div>

            <div class="lg:col-span-5">

                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-200">

                    <p class="text-sm uppercase tracking-[0.25em] text-slate-400 mb-6">
                        Технологии
                    </p>

                    @if($project->technologies)
                        <div class="flex flex-wrap gap-3">
                            @foreach(explode(',', $project->technologies) as $tech)
                                <span class="bg-slate-100 px-4 py-2 rounded-full text-sm text-slate-700">
                                    {{ trim($tech) }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    @if($project->github_link)
                        <a href="{{ $project->github_link }}" target="_blank"
                           class="inline-flex items-center gap-2 mt-8 text-blue-600 font-medium hover:gap-4 transition-all">
                            Github проекта →
                        </a>
                    @endif

                </div>

            </div>

        </div>

    </section>

    {{-- COVER IMAGE --}}
    @if($project->image)
        <section class="max-w-7xl mx-auto px-6 pb-28">
            <div class="overflow-hidden rounded-[2.5rem] shadow-2xl">
                <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}"
                     @click="open($event.currentTarget)"
                     data-lb-src="{{ asset('storage/' . $project->image) }}"
                     data-lb-group="lb-cover"
                     class="w-full aspect-[16/9] object-cover cursor-zoom-in transition duration-500 hover:scale-[1.02]">
            </div>
        </section>
    @endif

    {{-- CASE-STUDY BODY (shared renderer, mirrored by the CMS Live Preview) --}}
    @if($project->sections->isNotEmpty())
        <div class="max-w-7xl mx-auto px-6 pb-32">
            <p class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-16">
                Контент проекта
            </p>

            @include('partials.case-study', ['sections' => $project->sections])
        </div>
    @endif

    {{-- ───────────────── FULLSCREEN IMAGE VIEWER ───────────────── --}}
    <template x-teleport="body">
        <div
            x-show="shown"
            x-transition.opacity.duration.300ms
            @keydown.escape.window="shown && close()"
            @keydown.arrow-left.window="shown && prev()"
            @keydown.arrow-right.window="shown && next()"
            class="fixed inset-0 z-[9999] flex items-center justify-center"
            style="display: none"
            role="dialog"
            aria-modal="true"
        >
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/90 backdrop-blur-sm" @click="close()"></div>

            {{-- Close --}}
            <button type="button" @click="close()" aria-label="Закрыть"
                class="absolute top-4 right-4 md:top-6 md:right-6 z-20 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Prev --}}
            <button type="button" x-show="hasMultiple" @click.stop="prev()" aria-label="Назад"
                class="absolute left-3 md:left-8 z-20 w-12 h-12 md:w-14 md:h-14 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            {{-- Image --}}
            <div class="relative z-10 p-4" @click.stop>
                <img :src="current"
                     x-show="shown"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="max-w-[92vw] max-h-[86vh] w-auto h-auto object-contain rounded-lg shadow-2xl select-none"
                     alt="">
            </div>

            {{-- Next --}}
            <button type="button" x-show="hasMultiple" @click.stop="next()" aria-label="Вперёд"
                class="absolute right-3 md:right-8 z-20 w-12 h-12 md:w-14 md:h-14 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            {{-- Counter --}}
            <div x-show="hasMultiple"
                 class="absolute bottom-5 left-1/2 -translate-x-1/2 z-20 text-white/70 text-sm tracking-wide tabular-nums"
                 x-text="(index + 1) + ' / ' + images.length"></div>
        </div>
    </template>

</div>

@endsection
