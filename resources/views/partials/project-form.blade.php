{{--
    Shared project editor used by both create and edit.
    Params:
      $project     ?App\Models\Project  (null = create)
      $action      string  form action URL
      $heading     string
      $subheading  string
      $submitLabel string
--}}

@php
    $project = $project ?? null;

    $initialSections = [];
    foreach (optional($project)->sections ?? [] as $s) {
        $row = [
            'id'      => $s->id,
            'type'    => $s->type,
            'content' => $s->content ?? '',
            'meta'    => [
                'width'  => $s->meta['width'] ?? 'normal',
                'theme'  => $s->meta['theme'] ?? 'light',
                'group'  => $s->meta['group'] ?? '',
                'layout' => $s->meta['layout'] ?? 'left',
            ],
        ];
        if ($s->type === 'text') {
            $row['formats'] = ['bold' => false, 'italic' => false];
        }
        if ($s->type === 'image') {
            $row['preview'] = $s->content ? asset('storage/' . $s->content) : null;
        }
        if ($s->type === 'gallery') {
            $imgs = $s->meta['images'] ?? [];
            $row['existingImages'] = $imgs;
            $row['previews'] = array_map(fn ($p) => asset('storage/' . $p), $imgs);
        }
        $initialSections[] = $row;
    }

    $initial = [
        'title'       => old('title', optional($project)->title ?? ''),
        'description' => old('short_description', optional($project)->short_description ?? ''),
        'image'       => optional($project)->image ? asset('storage/' . $project->image) : null,
        'sections'    => $initialSections,
    ];

    $richPreview = 'rich-text [&_p]:mb-4 [&_p:last-child]:mb-0 [&_strong]:font-semibold [&_em]:italic';
@endphp

<div
    x-data="caseStudyForm({{ \Illuminate\Support\Js::from($initial) }})"
    class="grid lg:grid-cols-2 min-h-screen"
>

    {{-- ───────────────────────── EDITOR COLUMN ───────────────────────── --}}
    <div class="overflow-y-auto border-r border-slate-200 bg-slate-50">

        <div class="max-w-4xl mx-auto px-8 py-12">

            <div class="mb-12">
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-4">CMS Панель</p>
                <h1 class="text-5xl font-bold tracking-tight text-slate-900">{{ $heading }}</h1>
                <p class="text-slate-600 text-lg mt-4 max-w-2xl">{{ $subheading }}</p>
            </div>

            <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-8">

                @csrf

                <div class="bg-white border border-slate-200 rounded-[2rem] p-8">

                    <div class="space-y-6">

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-3">Название проекта</label>
                            <input type="text" name="title" x-model="previewTitle"
                                   placeholder="Например: Finance Mobile App"
                                   class="w-full rounded-2xl border border-slate-300 px-5 py-4 text-lg focus:outline-none focus:ring-2 focus:ring-slate-900">
                            @error('title')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                        </div>

                        {{-- Short description --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-3">Краткое описание</label>
                            <textarea name="short_description" x-model="previewDescription" rows="3"
                                      placeholder="Короткое описание проекта"
                                      class="w-full rounded-2xl border border-slate-300 px-5 py-4 text-lg focus:outline-none focus:ring-2 focus:ring-slate-900"></textarea>
                            @error('short_description')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                        </div>

                        {{-- Add-section buttons --}}
                        <div>
                            <div class="flex flex-wrap gap-4 mb-8">
                                <x-btn variant="primary" type="button"
                                    @click="sections.push({ id: Date.now() + Math.random(), type: 'text', content: '', meta: { width: 'normal', theme: 'light', group: '', layout: 'left' }, formats: { bold: false, italic: false } })">
                                    Добавить текст
                                </x-btn>
                                <x-btn variant="secondary" type="button"
                                    @click="sections.push({ id: Date.now() + Math.random(), type: 'title', content: '', meta: { width: 'normal', theme: 'light', group: '', layout: 'left' } })">
                                    Добавить заголовок
                                </x-btn>
                                <x-btn variant="secondary" type="button"
                                    @click="sections.push({ id: Date.now() + Math.random(), type: 'image', content: '', meta: { width: 'normal', theme: 'light', group: '', layout: 'left' }, preview: null })">
                                    Добавить изображение
                                </x-btn>
                                <x-btn variant="secondary" type="button"
                                    @click="sections.push({ id: Date.now() + Math.random(), type: 'gallery', content: '', meta: { width: 'normal', theme: 'light', group: '', layout: 'left' }, existingImages: [], previews: [] })">
                                    Галерея
                                </x-btn>
                            </div>

                            {{-- Sortable sections --}}
                            <div
                                x-ref="sectionsContainer"
                                x-init="Sortable.create($refs.sectionsContainer, {
                                    animation: 200, handle: '.drag-handle', ghostClass: 'opacity-50',
                                    onEnd(event) {
                                        const moved = sections.splice(event.oldIndex, 1)[0];
                                        sections.splice(event.newIndex, 0, moved);
                                    }
                                });"
                                class="space-y-6"
                            >
                                <template x-for="(section, index) in sections" :key="section.id">

                                    <div class="border border-slate-200 rounded-3xl p-6 bg-slate-50">

                                        <div class="flex justify-between items-start mb-4 gap-4">

                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-3">
                                                    <button type="button" class="drag-handle cursor-move text-slate-400 hover:text-slate-700 transition text-xl">⋮⋮</button>
                                                    <div>
                                                        <h3 class="font-semibold text-slate-800 capitalize" x-text="section.type"></h3>
                                                        <p class="text-sm text-slate-500 mt-1">Управление контентом блока</p>
                                                    </div>
                                                </div>

                                                <div class="grid md:grid-cols-2 gap-4 mt-6">
                                                    <div>
                                                        <label class="block text-sm text-slate-500 mb-2">Группа</label>
                                                        <input type="text" :name="'sections[' + index + '][meta][group]'" x-model="section.meta.group"
                                                               placeholder="напр. A — объединить блоки"
                                                               class="w-full rounded-xl border border-slate-300 px-4 py-3">
                                                    </div>
                                                    <template x-if="section.type === 'image' || section.type === 'gallery'">
                                                        <div>
                                                            <label class="block text-sm text-slate-500 mb-2">Сторона изображения</label>
                                                            <select :name="'sections[' + index + '][meta][layout]'" x-model="section.meta.layout"
                                                                    class="w-full rounded-xl border border-slate-300 px-4 py-3">
                                                                <option value="left">Слева</option>
                                                                <option value="right">Справа</option>
                                                            </select>
                                                        </div>
                                                    </template>
                                                    <div>
                                                        <label class="block text-sm text-slate-500 mb-2">Ширина блока</label>
                                                        <select :name="'sections[' + index + '][meta][width]'" x-model="section.meta.width"
                                                                class="w-full rounded-xl border border-slate-300 px-4 py-3">
                                                            <option value="narrow">Narrow</option>
                                                            <option value="normal">Normal</option>
                                                            <option value="wide">Wide</option>
                                                            <option value="full">Full</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm text-slate-500 mb-2">Тема блока</label>
                                                        <select :name="'sections[' + index + '][meta][theme]'" x-model="section.meta.theme"
                                                                class="w-full rounded-xl border border-slate-300 px-4 py-3">
                                                            <option value="light">Light</option>
                                                            <option value="dark">Dark</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button"
                                                @click="EditorRegistry.destroy(section.id); sections.splice(index, 1)"
                                                class="text-red-500 hover:text-red-700 transition shrink-0">
                                                Удалить
                                            </button>

                                        </div>

                                        {{-- TITLE --}}
                                        <template x-if="section.type === 'title'">
                                            <input type="text" :name="'sections[' + index + '][content]'" x-model="section.content"
                                                   placeholder="Например: ЗАДАЧА"
                                                   class="w-full rounded-2xl border border-slate-300 px-5 py-4 text-2xl font-bold focus:outline-none focus:ring-2 focus:ring-slate-900">
                                        </template>

                                        {{-- TEXT (TipTap: bold + italic only) --}}
                                        <template x-if="section.type === 'text'">
                                            <div
                                                x-init="EditorRegistry.mount(section.id, $refs.editor, $refs.input, section.content, {
                                                    onChange: (html) => { section.content = html },
                                                    onActiveStateChange: (formats) => { section.formats = formats },
                                                })"
                                                class="rounded-2xl overflow-hidden bg-white"
                                            >
                                                <input type="hidden" :name="'sections[' + index + '][content]'" x-ref="input">

                                                <div class="flex flex-wrap gap-2 border-b border-slate-200 bg-slate-50 p-3">
                                                    <button type="button" @click="EditorRegistry.command(section.id, 'bold')"
                                                            :class="section.formats?.bold ? 'bg-slate-900 text-white' : 'hover:bg-slate-200'"
                                                            class="px-4 py-2 rounded-xl transition font-bold">Bold</button>
                                                    <button type="button" @click="EditorRegistry.command(section.id, 'italic')"
                                                            :class="section.formats?.italic ? 'bg-slate-900 text-white' : 'hover:bg-slate-200'"
                                                            class="px-4 py-2 rounded-xl transition italic">Italic</button>
                                                </div>

                                                <div x-ref="editor" @click="EditorRegistry.focus(section.id)"
                                                     class="min-h-[250px] p-6 cursor-text"></div>
                                            </div>
                                        </template>

                                        {{-- IMAGE (with existing-image hydration + replace) --}}
                                        <template x-if="section.type === 'image'">
                                            <div class="space-y-4">
                                                {{-- Keep the existing stored path unless a new file is chosen --}}
                                                <input type="hidden" :name="'sections[' + index + '][content]'" :value="section.content">

                                                <template x-if="section.preview">
                                                    <div class="overflow-hidden rounded-2xl border border-slate-200">
                                                        <img :src="section.preview" class="w-full max-h-72 object-cover">
                                                    </div>
                                                </template>

                                                <input type="file" :name="'sections_images[' + index + ']'"
                                                    @change="const f = $event.target.files[0]; if (f) section.preview = URL.createObjectURL(f);"
                                                    class="block w-full text-sm text-slate-600 file:mr-6 file:rounded-xl file:border-0 file:bg-slate-900 file:px-6 file:py-3 file:text-white hover:file:bg-slate-700">

                                                <p class="text-sm text-slate-500"
                                                   x-text="section.preview ? 'Выберите файл, чтобы заменить изображение' : 'Загрузите изображение для секции'"></p>
                                            </div>
                                        </template>

                                        {{-- GALLERY (existing thumbnails + remove + add more) --}}
                                        <template x-if="section.type === 'gallery'">
                                            <div class="space-y-4">

                                                {{-- Persist kept existing images --}}
                                                <template x-for="(img, gi) in (section.existingImages || [])" :key="img">
                                                    <input type="hidden" :name="'sections[' + index + '][existing_images][]'" :value="img">
                                                </template>

                                                <template x-if="section.previews && section.previews.length">
                                                    <div class="grid grid-cols-3 gap-3">
                                                        <template x-for="(src, pi) in section.previews" :key="pi">
                                                            <div class="relative group overflow-hidden rounded-xl border border-slate-200">
                                                                <img :src="src" class="w-full h-24 object-cover">
                                                                <template x-if="section.existingImages && pi < section.existingImages.length">
                                                                    <button type="button"
                                                                        @click="section.existingImages.splice(pi, 1); section.previews.splice(pi, 1)"
                                                                        class="absolute top-1 right-1 bg-black/60 text-white rounded-full w-6 h-6 text-sm leading-none">×</button>
                                                                </template>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>

                                                <input type="file" multiple :name="'gallery_images[' + index + '][]'"
                                                    @change="
                                                        const keep = (section.existingImages || []).map(p => '/storage/' + p);
                                                        const added = Array.from($event.target.files).map(f => URL.createObjectURL(f));
                                                        section.previews = [...keep, ...added];
                                                    "
                                                    class="block w-full text-sm text-slate-600 file:mr-6 file:rounded-xl file:border-0 file:bg-slate-900 file:px-6 file:py-3 file:text-white hover:file:bg-slate-700">

                                                <p class="text-sm text-slate-500">Можно загрузить несколько изображений</p>
                                            </div>
                                        </template>

                                        <input type="hidden" :name="'sections[' + index + '][type]'" :value="section.type">

                                    </div>

                                </template>
                            </div>
                        </div>

                        {{-- Technologies --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-3">Технологии</label>
                            <input type="text" name="technologies"
                                   value="{{ old('technologies', optional($project)->technologies) }}"
                                   placeholder="Laravel, TailwindCSS, MySQL"
                                   class="w-full rounded-2xl border border-slate-300 px-5 py-4 text-lg focus:outline-none focus:ring-2 focus:ring-slate-900">
                            @error('technologies')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                        </div>

                        {{-- Date --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-3">Дата проекта</label>
                            <input type="date" name="project_date"
                                   value="{{ old('project_date', optional($project)->project_date) }}"
                                   class="w-full rounded-2xl border border-slate-300 px-5 py-4 text-lg focus:outline-none focus:ring-2 focus:ring-slate-900">
                            @error('project_date')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                        </div>

                    </div>

                </div>

                {{-- Cover image --}}
                <div
                    x-data="{ dragging: false, setCover(e) { const f = e.target.files[0]; if (f) previewImage = URL.createObjectURL(f); } }"
                    class="bg-white border border-slate-200 rounded-[2rem] p-8"
                >
                    <label class="block text-sm font-medium text-slate-700 mb-4">Обложка проекта</label>

                    <label
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="dragging = false; $refs.file.files = $event.dataTransfer.files; setCover({ target: { files: $event.dataTransfer.files } });"
                        :class="dragging ? 'border-slate-900 bg-slate-100' : 'border-slate-300 bg-slate-50'"
                        class="relative flex flex-col items-center justify-center border-2 border-dashed rounded-3xl p-16 transition cursor-pointer"
                    >
                        <input x-ref="file" type="file" name="image" @change="setCover" class="absolute inset-0 opacity-0 cursor-pointer">
                        <div class="text-center">
                            <div class="text-5xl mb-4">🖼️</div>
                            <p class="text-lg font-medium text-slate-700">Перетащите изображение сюда</p>
                            <p class="text-slate-500 mt-2">или нажмите для загрузки</p>
                        </div>
                    </label>

                    @error('image')<p class="text-red-500 text-sm mt-4">{{ $message }}</p>@enderror

                    <template x-if="previewImage">
                        <div class="mt-8">
                            <img :src="previewImage" class="w-full max-h-[500px] object-cover rounded-3xl shadow-lg">
                        </div>
                    </template>
                </div>

                <div class="flex items-center gap-3">
                    <x-btn variant="primary">{{ $submitLabel }}</x-btn>
                    <x-btn variant="secondary" href="/admin">Отмена</x-btn>
                </div>

            </form>
        </div>
    </div>

    {{-- ───────────────────────── LIVE PREVIEW COLUMN ───────────────────────── --}}
    {{-- Mirrors resources/views/partials/case-study.blade.php --}}
    <div class="hidden lg:block bg-white overflow-y-auto">

        <div class="p-12">

            <div class="max-w-4xl">

                <p class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-6">Live Preview</p>

                <h1 class="text-6xl font-bold tracking-tight text-slate-900 leading-none break-words">
                    <span x-text="previewTitle || 'Название проекта'"></span>
                </h1>

                <p class="text-xl text-slate-600 mt-8 leading-relaxed break-words">
                    <span x-text="previewDescription || 'Описание проекта появится здесь.'"></span>
                </p>

                <div class="mt-16 rounded-[2rem] overflow-hidden shadow-2xl border border-slate-200">
                    <template x-if="previewImage">
                        <img :src="previewImage" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!previewImage">
                        <div class="aspect-[16/10] bg-gradient-to-br from-slate-900 to-slate-700"></div>
                    </template>
                </div>

                <p x-show="compositions.length" class="text-sm uppercase tracking-[0.3em] text-slate-400 mt-20 mb-12">Контент проекта</p>

                <div class="space-y-20">

                    <template x-for="(c, ci) in compositions" :key="ci">

                        <div>

                            {{-- SHOWCASE: media + text, author-chosen side --}}
                            <template x-if="hasText(c) && hasMedia(c)">
                                <div :class="widthClass(c.meta.width) + (isDark(c) ? ' bg-slate-900 rounded-[2.5rem] p-6 md:p-10' : '')">
                                    <div class="grid lg:grid-cols-12 gap-8 items-center">

                                        <div class="lg:col-span-7 space-y-5" :class="mediaLeft(c) ? 'lg:order-1' : 'lg:order-2'">
                                            <template x-for="(img, ii) in c.images" :key="ii">
                                                <div class="overflow-hidden rounded-[2rem] shadow-xl">
                                                    <template x-if="img.preview"><img :src="img.preview" class="w-full aspect-[4/3] object-cover"></template>
                                                    <template x-if="!img.preview"><div class="aspect-[4/3] bg-slate-200"></div></template>
                                                </div>
                                            </template>
                                            <template x-if="c.gallery">
                                                <div class="grid grid-cols-2 md:grid-cols-6 auto-rows-[100px] md:auto-rows-[120px] gap-3">
                                                    <template x-for="(image, gi) in (c.gallery.previews || [])" :key="gi">
                                                        <div class="overflow-hidden rounded-2xl shadow-lg" :class="gallerySpan(gi, c.gallery.previews.length)">
                                                            <img :src="image" class="w-full h-full object-cover">
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="lg:col-span-5 min-w-0" :class="mediaLeft(c) ? 'lg:order-2' : 'lg:order-1'">
                                            <p class="text-xs uppercase tracking-[0.3em] mb-4" :class="isDark(c) ? 'text-white/40' : 'text-slate-400'"
                                               x-text="'Экран ' + String(ci + 1).padStart(2, '0')"></p>
                                            <template x-if="c.title">
                                                <h2 class="text-2xl md:text-3xl font-bold tracking-tight leading-tight break-words"
                                                    :class="isDark(c) ? 'text-white' : 'text-slate-900'"
                                                    x-text="c.title.content || 'Заголовок'"></h2>
                                            </template>
                                            <template x-for="(body, idx) in c.bodies" :key="idx">
                                                <div class="text-base leading-relaxed mt-4 {{ $richPreview }}"
                                                     :class="isDark(c) ? 'text-white/70' : 'text-slate-600'"
                                                     x-html="body.content || 'Текст секции'"></div>
                                            </template>
                                        </div>

                                    </div>
                                </div>
                            </template>

                            {{-- TEXT-ONLY: card (light) or cinematic dark block --}}
                            <template x-if="hasText(c) && !hasMedia(c)">
                                <div :class="widthClass(c.meta.width)">
                                    <template x-if="isDark(c)">
                                        <div class="bg-slate-900 rounded-[2.5rem] px-8 py-14 md:px-12 md:py-16">
                                            <p class="text-xs uppercase tracking-[0.3em] text-white/40 mb-6"
                                               x-text="'Раздел ' + String(ci + 1).padStart(2, '0')"></p>
                                            <template x-if="c.title">
                                                <h2 class="text-2xl md:text-4xl font-bold text-white leading-tight break-words mb-5"
                                                    x-text="c.title.content || 'Заголовок'"></h2>
                                            </template>
                                            <template x-for="(body, idx) in c.bodies" :key="idx">
                                                <div class="leading-tight {{ $richPreview }}"
                                                     :class="(c.title ? 'text-xl md:text-2xl text-white/70' : 'text-2xl md:text-3xl font-bold text-white') + (idx > 0 ? ' mt-5' : '')"
                                                     x-html="body.content || 'Текст'"></div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!isDark(c)">
                                        <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm p-8 md:p-10">
                                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400 mb-5"
                                               x-text="'Раздел ' + String(ci + 1).padStart(2, '0')"></p>
                                            <template x-if="c.title">
                                                <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900 leading-tight break-words mb-5"
                                                    x-text="c.title.content || 'Заголовок'"></h2>
                                            </template>
                                            <template x-for="(body, idx) in c.bodies" :key="idx">
                                                <div class="text-lg md:text-xl text-slate-700 leading-relaxed {{ $richPreview }}"
                                                     :class="idx > 0 ? 'mt-5' : ''"
                                                     x-html="body.content || 'Текст секции'"></div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            {{-- MEDIA-ONLY: full-width images and/or gallery --}}
                            <template x-if="!hasText(c) && hasMedia(c)">
                                <div :class="widthClass(c.meta.width) + (isDark(c) ? ' bg-slate-900 rounded-[2.5rem] p-6 md:p-10' : '')">
                                    <div class="space-y-6">
                                        <template x-for="(img, ii) in c.images" :key="ii">
                                            <div class="overflow-hidden rounded-[2rem] shadow-xl">
                                                <template x-if="img.preview"><img :src="img.preview" class="w-full object-cover"></template>
                                                <template x-if="!img.preview"><div class="aspect-[16/10] bg-slate-200"></div></template>
                                            </div>
                                        </template>
                                        <template x-if="c.gallery">
                                            <div>
                                                <template x-if="c.gallery.previews && c.gallery.previews.length">
                                                    <div class="grid grid-cols-2 md:grid-cols-6 auto-rows-[120px] md:auto-rows-[140px] gap-3">
                                                        <template x-for="(image, gi) in c.gallery.previews" :key="gi">
                                                            <div class="overflow-hidden rounded-2xl shadow-lg" :class="gallerySpan(gi, c.gallery.previews.length)">
                                                                <img :src="image" class="w-full h-full object-cover">
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                                <template x-if="!c.gallery.previews || !c.gallery.previews.length">
                                                    <div class="aspect-[16/9] rounded-2xl bg-slate-200 flex items-center justify-center text-slate-400 text-sm">
                                                        Галерея — загрузите изображения
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                        </div>

                    </template>

                </div>

            </div>

        </div>

    </div>

</div>
