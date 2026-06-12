@props([
    'variant' => 'secondary',
    'href' => null,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 whitespace-nowrap';

    $variants = [
        'primary'   => 'bg-slate-900 text-white hover:bg-slate-700',
        'secondary' => 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-100',
        'danger'    => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-600',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['secondary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
