<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectSectionUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_and_gallery_sections_persist_uploaded_files(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/create', [
            'title' => 'Case Study',
            'short_description' => 'desc',
            'technologies' => 'Laravel',
            'sections' => [
                0 => ['type' => 'title', 'content' => 'ЗАДАЧА'],
                1 => ['type' => 'text', 'content' => '<p>body</p>'],
                2 => ['type' => 'image'],
                3 => ['type' => 'gallery'],
            ],
            'sections_images' => [
                2 => UploadedFile::fake()->image('shot.png'),
            ],
            'gallery_images' => [
                3 => [
                    UploadedFile::fake()->image('g1.png'),
                    UploadedFile::fake()->image('g2.png'),
                ],
            ],
        ]);

        $response->assertRedirect('/admin');

        $project = Project::with('sections')->firstWhere('title', 'Case Study');
        $this->assertNotNull($project);

        $sections = $project->sections;
        $this->assertCount(4, $sections, 'all four sections should persist');

        // Image section keeps the stored path and the file exists on disk.
        $image = $sections->firstWhere('type', 'image');
        $this->assertNotNull($image->content, 'image content path must not be null');
        Storage::disk('public')->assertExists($image->content);

        // Gallery section persists both images in meta and they exist on disk.
        $gallery = $sections->firstWhere('type', 'gallery');
        $this->assertNotEmpty($gallery->meta['images'] ?? [], 'gallery meta images must persist');
        $this->assertCount(2, $gallery->meta['images']);
        foreach ($gallery->meta['images'] as $path) {
            Storage::disk('public')->assertExists($path);
        }
    }

    public function test_manual_group_and_layout_persist_in_meta(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/create', [
            'title' => 'Grouped',
            'sections' => [
                0 => ['type' => 'title', 'content' => 'Head', 'meta' => ['group' => 'A', 'layout' => 'right', 'width' => 'wide']],
                1 => ['type' => 'text', 'content' => '<p>body</p>', 'meta' => ['group' => 'A', 'layout' => 'right']],
                2 => ['type' => 'text', 'content' => '<p>ungrouped</p>', 'meta' => ['group' => '']],
            ],
        ])->assertRedirect('/admin');

        $sections = Project::firstWhere('title', 'Grouped')->sections;

        $this->assertSame('A', $sections[0]->meta['group']);
        $this->assertSame('right', $sections[0]->meta['layout']);
        $this->assertSame('wide', $sections[0]->meta['width']);
        $this->assertSame('A', $sections[1]->meta['group']);
        // Empty group string is not persisted (auto fallback).
        $this->assertArrayNotHasKey('group', $sections[2]->meta ?? []);
    }
}
