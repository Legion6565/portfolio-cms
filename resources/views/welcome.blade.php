@extends('layouts.app')

@section('content')

<section class="pt-12 pb-12">

    <div class="max-w-5xl mx-auto px-6 text-center">

        <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-slate-900">
            Дипломная работа
        </h1>

        <p class="text-xl text-slate-600 mt-6 max-w-2xl mx-auto leading-relaxed">
            Система управления контентом сайта-портфолио,
            разработанная на Laravel, Tailwind CSS и MySQL.
        </p>

    </div>

</section>

<section class="max-w-7xl mx-auto px-6 pb-24">

    <div class="flex items-center justify-between mb-10">

        <h2 class="text-3xl md:text-4xl font-bold text-slate-900">
            Проекты
        </h2>

    </div>

    <div class="grid md:grid-cols-2 gap-8">

        @foreach($projects as $project)

            <x-project-card :project="$project" />

        @endforeach

    </div>

</section>

@endsection