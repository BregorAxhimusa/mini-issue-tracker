<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $users = User::factory(5)->create();
        $tags = Tag::factory(8)->create();

        Project::factory(5)->create()->each(function (Project $project) use ($tags, $users) {
            $issues = Issue::factory(rand(4, 8))->for($project)->create();

            $issues->each(function (Issue $issue) use ($tags, $users) {
                $issue->tags()->attach($tags->random(rand(1, 3))->pluck('id'));

                $assignees = $users->random(rand(0, 2));
                if ($assignees->isNotEmpty()) {
                    $issue->assignees()->attach($assignees->pluck('id'));
                }

                Comment::factory(rand(2, 5))->for($issue)->create();
            });
        });
    }
}
