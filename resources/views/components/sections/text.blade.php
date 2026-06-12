@php

    $width = $section->meta['width'] ?? 'normal';

    $theme = $section->meta['theme'] ?? 'light';

    $widthClass = match($width) {

        'narrow' => 'max-w-2xl',

        'wide' => 'max-w-6xl',

        default => 'max-w-3xl'

    };

@endphp

<div class="{{ $widthClass }}">

    <div
        class="
            text-xl
            leading-10
            whitespace-pre-line
            rounded-[2rem]
            p-8

            {{ $theme === 'dark'
                ? 'bg-slate-900 text-white'
                : 'text-slate-700'
            }}
        "
    >
        {!! $section->content !!}
    </div>

</div>