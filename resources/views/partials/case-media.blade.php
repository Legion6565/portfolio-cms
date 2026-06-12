{{--
    Renders the media side of a composition: stacked images + optional gallery.
    Params: $images (collection of image sections), $gallery (section|null),
            $gallerySpan (closure), $framed (bool — fixed aspect for showcase columns)

    Images are clickable → opens the fullscreen viewer (resources/js/lightbox.js).
    `data-lb-group` groups images that navigate together (stacked images = one
    group, the gallery = another).
--}}
@php
    $imagesGroup  = 'lb-img-' . \Illuminate\Support\Str::random(8);
    $galleryGroup = 'lb-gal-' . \Illuminate\Support\Str::random(8);
@endphp

<div class="space-y-6">

    @foreach($images as $img)
        @php $src = asset('storage/' . $img->content); @endphp
        <div class="overflow-hidden rounded-[2rem] shadow-2xl">
            <img src="{{ $src }}" alt=""
                 @click="open($event.currentTarget)"
                 data-lb-src="{{ $src }}"
                 data-lb-group="{{ $imagesGroup }}"
                 class="w-full {{ $framed ? 'aspect-[4/3]' : '' }} object-cover cursor-zoom-in transition duration-500 hover:scale-[1.03]">
        </div>
    @endforeach

    @if($gallery)
        @php $gi = $gallery->meta['images'] ?? []; $gc = count($gi); @endphp
        @if($gc)
            <div class="grid grid-cols-2 md:grid-cols-6 auto-rows-[140px] md:auto-rows-[200px] gap-4">
                @foreach($gi as $i => $image)
                    @php $src = asset('storage/' . $image); @endphp
                    <div class="overflow-hidden rounded-[1.75rem] shadow-xl {{ $gallerySpan($i, $gc) }}">
                        <img src="{{ $src }}" alt=""
                             @click="open($event.currentTarget)"
                             data-lb-src="{{ $src }}"
                             data-lb-group="{{ $galleryGroup }}"
                             class="w-full h-full object-cover cursor-zoom-in transition duration-500 hover:scale-[1.05]">
                    </div>
                @endforeach
            </div>
        @endif
    @endif

</div>
