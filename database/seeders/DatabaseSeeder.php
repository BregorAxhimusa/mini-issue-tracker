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
        // Demo accounts (password for every seeded user is "password"):
        //   alice@example.com / password
        //   bob@example.com   / password
        $alice = User::factory()->create([
            'name' => 'Alice Owner',
            'email' => 'alice@example.com',
        ]);

        $bob = User::factory()->create([
            'name' => 'Bob Owner',
            'email' => 'bob@example.com',
        ]);

        $owners = collect([$alice, $bob]);
        $members = $owners->merge(User::factory(4)->create());
        $tags = Tag::factory(8)->create();

        collect(range(1, 5))->each(function () use ($owners, $members, $tags) {
            $project = Project::factory()->for($owners->random(), 'owner')->create();

            Issue::factory(rand(4, 8))->for($project)->create()->each(function (Issue $issue) use ($tags, $members) {
                $issue->tags()->attach($tags->random(rand(1, 3))->pluck('id'));

                $assignees = $members->random(rand(0, 2));
                if ($assignees->isNotEmpty()) {
                    $issue->assignees()->attach($assignees->pluck('id'));
                }

                Comment::factory(rand(2, 5))->for($issue)->create();
            });
        });
    }
}
