{{--
    Shared case-study renderer (composition model).
    Expects: $sections (iterable of objects with ->type, ->content, ->meta)

    Grouping priority:
      1. MANUAL — contiguous sections sharing a non-empty meta.group form ONE
         composition; author picks media side via meta.layout (left|right).
      2. AUTO   — ungrouped sections fall back to automatic composition
         (title/text + image → showcase with alternating sides, etc.).

    The CMS Live Preview mirrors this in resources/js/preview.js — keep in sync.
--}}

@php
    $rich = 'rich-text [&_p]:mb-5 [&_p:last-child]:mb-0 [&_strong]:font-semibold [&_em]:italic';

    $widthClass = fn (?string $w) => match ($w) {
        'narrow' => 'max-w-2xl mx-auto',
        'wide'   => 'max-w-6xl mx-auto',
        'full'   => 'max-w-none',
        default  => 'max-w-4xl mx-auto',
    };

    $metaOf = function (array $secs): array {
        foreach ($secs as $s) {
            if ($s && ! empty($s->meta)) return $s->meta;
        }
        return ['width' => 'normal', 'theme' => 'light'];
    };

    $gallerySpan = function (int $i, int $count): string {
        if ($count === 1) return 'col-span-2 row-span-2 md:col-span-6';
        if ($count === 2) return 'col-span-1 row-span-2 md:col-span-3';
        if ($count === 3) return $i === 0
            ? 'col-span-2 row-span-2 md:col-span-4'
            : 'col-span-1 md:col-span-2';
        $pattern = [
            'col-span-2 row-span-2 md:col-span-4',
            'col-span-1 md:col-span-2',
            'col-span-1 md:col-span-2',
            'col-span-1 md:col-span-3',
            'col-span-1 md:col-span-3',
        ];
        return $pattern[$i % count($pattern)];
    };

    // ── Split sections into runs (manual groups vs auto runs) ──────
    $runs = [];
    foreach ($sections as $s) {
        $g = trim($s->meta['group'] ?? '');
        $last = count($runs) ? $runs[count($runs) - 1] : null;
        if ($g !== '') {
            if ($last && $last['type'] === 'explicit' && $last['group'] === $g) {
                $runs[count($runs) - 1]['sections'][] = $s;
            } else {
                $runs[] = ['type' => 'explicit', 'group' => $g, 'sections' => [$s]];
            }
        } else {
            if ($last && $last['type'] === 'auto') {
                $runs[count($runs) - 1]['sections'][] = $s;
            } else {
                $runs[] = ['type' => 'auto', 'sections' => [$s]];
            }
        }
    }

    // ── Build compositions ─────────────────────────────────────────
    $mediaCounter = 0;

    $make = function (array $secs, ?string $explicitLayout) use (&$mediaCounter, $metaOf) {
        $secs    = collect($secs);
        $title   = $secs->firstWhere('type', 'title');
        $bodies  = $secs->where('type', 'text')->values();
        $images  = $secs->where('type', 'image')->values();
        $gallery = $secs->firstWhere('type', 'gallery');

        // Width/theme come from the group's leading section (original order).
        $meta = $metaOf($secs->all());
        $hasMedia = $images->count() > 0 || $gallery;

        if ($explicitLayout) {
            $layout = $explicitLayout;
        } else {
            $layout = $hasMedia ? ($mediaCounter % 2 === 0 ? 'left' : 'right') : 'left';
        }
        if ($hasMedia) $mediaCounter++;

        return compact('title', 'bodies', 'images', 'gallery', 'meta', 'layout');
    };

    $compositions = [];

    foreach ($runs as $run) {
        if ($run['type'] === 'explicit') {
            // Orientation only comes from media sections (title/text never carry it).
            $layout = null;
            foreach ($run['sections'] as $s) {
                if (in_array($s->type, ['image', 'gallery']) && ! empty($s->meta['layout'])) {
                    $layout = $s->meta['layout'];
                    break;
                }
            }
            $compositions[] = $make($run['sections'], $layout ?: 'left');
        } else {
            $pending = [];
            foreach ($run['sections'] as $s) {
                if (in_array($s->type, ['title', 'text'])) {
                    if ($s->type === 'title' && ! empty($pending)) {
                        $compositions[] = $make($pending, null);
                        $pending = [];
                    }
                    $pending[] = $s;
                    continue;
                }
                if ($s->type === 'image') {
                    if (! empty($pending)) {
                        $pending[] = $s;
                        $compositions[] = $make($pending, null);
                        $pending = [];
                    } else {
                        $compositions[] = $make([$s], null);
                    }
                    continue;
                }
                if ($s->type === 'gallery') {
                    if (! empty($pending)) {
                        $compositions[] = $make($pending, null);
                        $pending = [];
                    }
                    $compositions[] = $make([$s], null);
                }
            }
            if (! empty($pending)) $compositions[] = $make($pending, null);
        }
    }

    $chapter = 0;
@endphp

<div class="space-y-28 md:space-y-40">

    @foreach($compositions as $c)

        @php
            $chapter++; $n = sprintf('%02d', $chapter);
            $width   = $widthClass($c['meta']['width'] ?? 'normal');
            $dark    = ($c['meta']['theme'] ?? 'light') === 'dark';
            $hasText = $c['title'] || $c['bodies']->count();
            $hasMedia = $c['images']->count() || $c['gallery'];
            $mediaLeft = ($c['layout'] ?? 'left') === 'left';
        @endphp

        @if($hasText && $hasMedia)

            {{-- Showcase: media + text, side chosen by author (or auto-alternated) --}}
            <div class="{{ $width }} {{ $dark ? 'bg-slate-900 rounded-[2.5rem] p-8 md:p-12' : '' }}">
                <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-center">

                    <div class="lg:col-span-7 space-y-6 {{ $mediaLeft ? 'lg:order-1' : 'lg:order-2' }}">
                        @include('partials.case-media', ['images' => $c['images'], 'gallery' => $c['gallery'], 'gallerySpan' => $gallerySpan, 'framed' => true])
                    </div>

                    <div class="lg:col-span-5 min-w-0 {{ $mediaLeft ? 'lg:order-2' : 'lg:order-1' }}">
                        <p class="text-xs uppercase tracking-[0.3em] mb-5 {{ $dark ? 'text-white/40' : 'text-slate-400' }}">Экран {{ $n }}</p>
                        @if($c['title'])
                            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight leading-tight break-words {{ $dark ? 'text-white' : 'text-slate-900' }}">
                                {{ $c['title']->content }}
                            </h2>
                        @endif
                        @foreach($c['bodies'] as $body)
                            <div class="text-base md:text-lg leading-relaxed mt-6 {{ $rich }} {{ $dark ? 'text-white/70' : 'text-slate-600' }}">
                                {!! $body->content !!}
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

        @elseif($hasText)

            {{-- Text-only: white card (light) or cinematic dark block --}}
            @if($dark)
                <div class="{{ $width }} bg-slate-900 rounded-[2.5rem] px-8 py-16 md:px-20 md:py-24">
                    <p class="text-xs uppercase tracking-[0.3em] text-white/40 mb-8">Раздел {{ $n }}</p>
                    @if($c['title'])
                        <h2 class="text-3xl md:text-5xl lg:text-6xl font-bold tracking-tight text-white leading-tight break-words mb-8">
                            {{ $c['title']->content }}
                        </h2>
                        @foreach($c['bodies'] as $body)
                            <div class="text-xl md:text-2xl text-white/70 leading-relaxed {{ $rich }} {{ !$loop->first ? 'mt-6' : '' }}">{!! $body->content !!}</div>
                        @endforeach
                    @else
                        @foreach($c['bodies'] as $body)
                            <div class="text-3xl md:text-5xl lg:text-6xl font-bold text-white leading-tight {{ $rich }} {{ !$loop->first ? 'mt-6' : '' }}">{!! $body->content !!}</div>
                        @endforeach
                    @endif
                </div>
            @else
                <div class="{{ $width }} bg-white border border-slate-200 rounded-[2rem] md:rounded-[2.5rem] shadow-sm p-8 md:p-14">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400 mb-6">Раздел {{ $n }}</p>
                    @if($c['title'])
                        <h2 class="text-2xl md:text-4xl font-bold tracking-tight text-slate-900 leading-tight break-words mb-6">
                            {{ $c['title']->content }}
                        </h2>
                    @endif
                    @foreach($c['bodies'] as $body)
                        <div class="text-xl md:text-2xl text-slate-700 leading-relaxed {{ $rich }} {{ !$loop->first ? 'mt-6' : '' }}">{!! $body->content !!}</div>
                    @endforeach
                </div>
            @endif

        @elseif($hasMedia)

            {{-- Media-only: full-width images and/or gallery --}}
            <div class="{{ $width }} {{ $dark ? 'bg-slate-900 rounded-[2.5rem] p-6 md:p-10' : '' }}">
                @include('partials.case-media', ['images' => $c['images'], 'gallery' => $c['gallery'], 'gallerySpan' => $gallerySpan, 'framed' => false])
            </div>

        @endif

    @endforeach

</div>
