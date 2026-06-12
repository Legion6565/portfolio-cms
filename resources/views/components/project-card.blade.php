<a
    href="/project/{{ $project->id }}"
    class="group relative overflow-hidden rounded-[2rem] bg-slate-900 min-h-[420px]"
>

    @if($project->image)

        <img
            src="{{ asset('storage/' . $project->image) }}"
            alt="{{ $project->title }}"
            class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700"
        >

    @else

        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-800"></div>

    @endif

    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

    <div class="absolute bottom-0 left-0 p-8 text-white w-full">

        <div class="flex items-center justify-between gap-4">

            <h3 class="text-3xl font-bold leading-tight">
                {{ $project->title }}
            </h3>

            <span class="opacity-0 group-hover:opacity-100 transition text-2xl">
                →
            </span>

        </div>

        <p class="mt-4 text-white/80 max-w-md leading-relaxed opacity-0 translate-y-4 group-hover:translate-y-0 group-hover:opacity-100 transition duration-500">
            {{ $project->short_description }}
        </p>

    </div>

</a>