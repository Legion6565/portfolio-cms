@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-12">

    <div class="flex items-center justify-between mb-12">

        <div>

            <p class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-3">
                CMS Панель
            </p>

            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-slate-900">
                Управление проектами
            </h1>

        </div>

        <x-btn variant="primary" href="/admin/create">
            Новый проект
        </x-btn>

    </div>

    <div class="grid gap-6">

        @foreach($projects as $project)

            <div class="bg-white border border-slate-200 rounded-[2rem] overflow-hidden">

                <div class="grid lg:grid-cols-12">

                    <div class="lg:col-span-4">

                        @if($project->image)

                            <img
                                src="{{ asset('storage/' . $project->image) }}"
                                alt="{{ $project->title }}"
                                class="w-full h-full object-cover min-h-[260px]"
                            >

                        @else

                            <div class="h-full min-h-[260px] bg-gradient-to-br from-blue-500 to-indigo-600"></div>

                        @endif

                    </div>

                    <div class="lg:col-span-8 p-8 flex flex-col justify-between">

                        <div>

                            <h2 class="text-3xl font-bold text-slate-900">
                                {{ $project->title }}
                            </h2>

                            <p class="text-slate-600 text-lg mt-4 leading-relaxed max-w-3xl">
                                {{ $project->short_description }}
                            </p>

                            @if($project->technologies)

                                <div class="flex flex-wrap gap-3 mt-6">

                                    @foreach(explode(',', $project->technologies) as $tech)

                                        <span class="bg-slate-100 px-4 py-2 rounded-full text-sm text-slate-700">
                                            {{ trim($tech) }}
                                        </span>

                                    @endforeach

                                </div>

                            @endif

                        </div>

                        <div class="flex flex-wrap items-center gap-3 mt-10">

                            <x-btn variant="secondary" href="/project/{{ $project->id }}" target="_blank">
                                Открыть
                            </x-btn>

                            <x-btn variant="primary" href="/admin/edit/{{ $project->id }}">
                                Редактировать
                            </x-btn>

                            <div x-data="{ open: false }" class="inline-flex">

                                <x-btn variant="danger" @click="open = true">
                                    Удалить
                                </x-btn>

                                <div
                                    x-show="open"
                                    class="fixed inset-0 z-50"
                                >

                                    <!-- BACKDROP -->

                                    <div
                                        class="absolute inset-0 bg-black/40"
                                    ></div>

                                    <!-- MODAL -->

                                    <div class="flex items-center justify-center min-h-screen p-4">

                                        <div
                                            x-show="open"

                                            x-transition:enter="transition transform ease-out duration-300"
                                            x-transition:enter-start="opacity-0 scale-95 translate-y-6"
                                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"

                                            x-transition:leave="transition transform ease-in duration-200"
                                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 scale-95 translate-y-6"

                                            @click.outside="open = false"

                                            class="relative bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl"
                                        >

                                            <h2 class="text-2xl font-bold text-slate-900 mb-4">
                                                Удалить проект?
                                            </h2>

                                            <p class="text-slate-600 leading-7">
                                                Проект будет удалён без возможности восстановления.
                                            </p>

                                            <div class="flex justify-end gap-3 mt-8">

                                                <x-btn variant="secondary" @click="open = false">
                                                    Отмена
                                                </x-btn>

                                                <form
                                                    action="/admin/delete/{{ $project->id }}"
                                                    method="POST"
                                                >

                                                    @csrf

                                                    <x-btn variant="danger">
                                                        Удалить
                                                    </x-btn>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection