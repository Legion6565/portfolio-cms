<div class="grid md:grid-cols-2 gap-6">

    @foreach($section->meta['images'] ?? [] as $image)

        <div class="overflow-hidden rounded-[2rem] shadow-xl">

            <img
                src="{{ asset('storage/' . $image) }}"
                class="w-full h-full object-cover"
            >

        </div>

    @endforeach

</div>