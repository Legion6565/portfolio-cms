<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class AdminProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('admin.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store()
    {
        $validated = request()->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'technologies' => ['nullable', 'string'],
            'project_date' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $imagePath = null;

        if (request()->hasFile('image')) {

            $imagePath = request()
                ->file('image')
                ->store('projects', 'public');
        }

        $project = Project::create([

            'title' => $validated['title'],

            'short_description' => $validated['short_description'] ?? null,

            'technologies' => $validated['technologies'] ?? null,

            'project_date' => $validated['project_date'] ?? null,

            'image' => $imagePath,

        ]);

        $this->saveSections($project);

        return redirect('/admin')
            ->with('success', 'Проект создан');
    }

    /**
     * Persist the submitted section blocks for a project.
     *
     * Resolves content per type BEFORE deciding whether a block is empty,
     * so image/gallery blocks (whose content comes from uploaded files,
     * not a text field) are never skipped.
     */
    protected function saveSections(Project $project): void
    {
        foreach (request()->input('sections', []) as $index => $section) {

            $type = $section['type'] ?? null;
            $content = $section['content'] ?? null;
            $meta = array_filter([
                'width'  => $section['meta']['width'] ?? null,
                'theme'  => $section['meta']['theme'] ?? null,
                'group'  => isset($section['meta']['group']) ? trim($section['meta']['group']) : null,
                'layout' => $section['meta']['layout'] ?? null,
            ], fn ($v) => $v !== null && $v !== '');

            switch ($type) {

                case 'image':
                    // New upload wins; otherwise keep the existing stored path
                    // (passed back as a hidden field on edit).
                    if (request()->hasFile("sections_images.$index")) {
                        $content = request()
                            ->file("sections_images.$index")
                            ->store('projects/sections', 'public');
                    }
                    if (! $content) {
                        continue 2; // no image at all — nothing to store
                    }
                    break;

                case 'gallery':
                    $images = [];

                    // Preserve already-stored images submitted as hidden fields.
                    foreach ($section['existing_images'] ?? [] as $existing) {
                        if ($existing) {
                            $images[] = $existing;
                        }
                    }

                    if (request()->hasFile("gallery_images.$index")) {
                        foreach (request()->file("gallery_images.$index") as $image) {
                            $images[] = $image->store('projects/gallery', 'public');
                        }
                    }

                    if (empty($images)) {
                        continue 2; // empty gallery — skip
                    }

                    $meta['images'] = $images;
                    $content = null;
                    break;

                case 'title':
                case 'text':
                    if (! filled($content)) {
                        continue 2; // empty text block — skip
                    }
                    break;

                default:
                    continue 2; // unknown type — ignore
            }

            $project->sections()->create([
                'type' => $type,
                'content' => $content,
                'meta' => $meta ?: null,
                'position' => $index,
            ]);
        }
    }

    public function edit(Project $project)
    {
        $project->load('sections');

        return view('admin.edit', compact('project'));
    }

    public function update(Project $project)
    {
        $validated = request()->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'technologies' => ['nullable', 'string'],
            'project_date' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        // Replace the cover only when a new file is uploaded; otherwise keep it.
        if (request()->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $validated['image'] = request()->file('image')->store('projects', 'public');
        } else {
            unset($validated['image']);
        }

        $project->update($validated);

        $project->sections()->delete();

        $this->saveSections($project);

        return redirect('/admin')
            ->with('success', 'Проект обновлён');
    }

    public function destroy(Project $project)
    {
        if ($project->image) {

            Storage::disk('public')->delete($project->image);

        }

        $project->delete();

        return redirect('/admin')->with('success', 'Проект удалён');
    }
}