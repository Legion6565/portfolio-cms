<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_preserves_existing_media_without_reupload(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        // Seed a project with an image + gallery section and a cover.
        $project = Project::create([
            'title' => 'Original',
            'short_description' => 'orig',
            'image' => 'projects/cover.png',
        ]);
        $project->sections()->create([
            'type' => 'image', 'content' => 'projects/sections/keep.png',
            'meta' => null, 'position' => 0,
        ]);
        $project->sections()->create([
            'type' => 'gallery', 'content' => null,
            'meta' => ['images' => ['projects/gallery/a.png', 'projects/gallery/b.png']],
            'position' => 1,
        ]);

        // Edit form re-submits existing paths as hidden fields, no new uploads,
        // and changes the title.
        $response = $this->actingAs($user)->post('/admin/update/' . $project->id, [
            'title' => 'Updated Title',
            'short_description' => 'orig',
            'sections' => [
                0 => ['type' => 'image', 'content' => 'projects/sections/keep.png'],
                1 => ['type' => 'gallery', 'existing_images' => ['projects/gallery/a.png', 'projects/gallery/b.png']],
            ],
        ]);

        $response->assertRedirect('/admin');

        $project->refresh()->load('sections');
        $this->assertSame('Updated Title', $project->title);
        $this->assertSame('projects/cover.png', $project->image, 'cover preserved when no new upload');

        $image = $project->sections->firstWhere('type', 'image');
        $this->assertSame('projects/sections/keep.png', $image->content, 'image path preserved');

        $gallery = $project->sections->firstWhere('type', 'gallery');
        $this->assertSame(
            ['projects/gallery/a.png', 'projects/gallery/b.png'],
            $gallery->meta['images'],
            'gallery images preserved'
        );
    }

    public function test_edit_replaces_cover_when_new_file_uploaded(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $project = Project::create([
            'title' => 'Original',
            'image' => 'projects/old-cover.png',
        ]);

        $this->actingAs($user)->post('/admin/update/' . $project->id, [
            'title' => 'Original',
            'image' => UploadedFile::fake()->image('new-cover.png'),
        ])->assertRedirect('/admin');

        $project->refresh();
        $this->assertNotSame('projects/old-cover.png', $project->image, 'cover replaced');
        Storage::disk('public')->assertExists($project->image);
    }
}
