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

        $owners = [$alice, $bob];

        $members = collect([$alice, $bob]);
        foreach ($this->members() as $member) {
            $members->push(User::factory()->create($member));
        }

        $tags = collect($this->tags())->map(
            fn (array $tag) => Tag::factory()->create($tag)
        );

        $comments = $this->comments();
        $issueGroups = array_chunk($this->issuePool(), 4);
        $issueIndex = 0;

        foreach ($this->projects() as $index => $data) {
            $project = Project::factory()
                ->for($owners[$index % count($owners)], 'owner')
                ->create([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'start_date' => now()->subDays($data['start_days']),
                    'deadline' => now()->subDays($data['start_days'])->addMonths($data['months']),
                ]);

            foreach ($issueGroups[$index] as $issueData) {
                $issue = Issue::factory()->for($project)->create([
                    'title' => $issueData['title'],
                    'description' => $issueData['description'],
                ]);

                $issue->tags()->attach($tags->random(rand(1, 3))->pluck('id'));

                $assignees = $members->random(rand(0, 2));
                if ($assignees->isNotEmpty()) {
                    $issue->assignees()->attach($assignees->pluck('id'));
                }

                $commentCount = 2 + ($issueIndex % 3);
                for ($i = 0; $i < $commentCount; $i++) {
                    Comment::factory()->for($issue)->create([
                        'author_name' => $members[($issueIndex + $i) % $members->count()]->name,
                        'body' => $comments[($issueIndex + $i) % count($comments)],
                    ]);
                }

                $issueIndex++;
            }
        }
    }

    private function members(): array
    {
        return [
            ['name' => 'Carol Mendes', 'email' => 'carol@example.com'],
            ['name' => 'David Park', 'email' => 'david@example.com'],
            ['name' => 'Emma Thompson', 'email' => 'emma@example.com'],
            ['name' => 'Frank Rivera', 'email' => 'frank@example.com'],
        ];
    }

    private function projects(): array
    {
        return [
            ['name' => 'Customer Portal Redesign', 'description' => 'Rework the customer-facing portal with a cleaner layout and simpler navigation.', 'start_days' => 80, 'months' => 4],
            ['name' => 'Mobile App v2', 'description' => 'Second major release of the mobile app with offline support and a refreshed interface.', 'start_days' => 65, 'months' => 5],
            ['name' => 'Billing System Migration', 'description' => 'Move the legacy billing system onto the new platform with no customer downtime.', 'start_days' => 45, 'months' => 3],
            ['name' => 'Internal Admin Dashboard', 'description' => 'Build an internal dashboard for the support team to manage accounts and orders.', 'start_days' => 30, 'months' => 6],
            ['name' => 'API Performance Optimization', 'description' => 'Reduce response times and database load across the public and internal APIs.', 'start_days' => 15, 'months' => 4],
        ];
    }

    private function tags(): array
    {
        return [
            ['name' => 'bug', 'color' => '#ef4444'],
            ['name' => 'feature', 'color' => '#3b82f6'],
            ['name' => 'enhancement', 'color' => '#8b5cf6'],
            ['name' => 'urgent', 'color' => '#dc2626'],
            ['name' => 'backend', 'color' => '#0ea5e9'],
            ['name' => 'frontend', 'color' => '#14b8a6'],
            ['name' => 'database', 'color' => '#f59e0b'],
            ['name' => 'ui', 'color' => '#ec4899'],
            ['name' => 'performance', 'color' => '#22c55e'],
            ['name' => 'security', 'color' => '#6366f1'],
        ];
    }

    private function comments(): array
    {
        return [
            'I can reproduce this on staging.',
            'Assigning this to the backend team.',
            'Fixed in the latest commit, please verify.',
            'Is this still happening?',
            'Added tests for this case.',
            'Let us prioritize this for the next sprint.',
            'Confirmed, this is a regression from the last release.',
        ];
    }

    private function issuePool(): array
    {
        return [
            ['title' => 'Login fails with valid credentials on Safari', 'description' => 'Users on Safari are rejected even with the correct credentials. The same login works on Chrome and Firefox.'],
            ['title' => 'Add pagination to the orders table', 'description' => 'The orders table loads every record at once and becomes slow. Add server-side pagination.'],
            ['title' => 'Dashboard charts not loading on first render', 'description' => 'The charts appear blank until the page is refreshed. Likely a timing issue with the data fetch.'],
            ['title' => 'Export to CSV times out for large datasets', 'description' => 'Exporting more than a few thousand rows hits the request timeout. Move it to a queued export.'],
            ['title' => 'Password reset email not being sent', 'description' => 'Users report not receiving the reset link. Check the mail queue and the template.'],
            ['title' => 'Improve mobile layout on the checkout page', 'description' => 'The checkout form overflows on small screens. Adjust the spacing and stacking for mobile.'],
            ['title' => 'Cache invalidation bug after profile update', 'description' => 'Stale profile data is shown after saving changes. The cache key is not being cleared.'],
            ['title' => 'Add dark mode toggle to settings', 'description' => 'Allow users to switch between light and dark themes from the settings page.'],
            ['title' => 'Search returns duplicate results', 'description' => 'Some queries list the same record more than once. A join is producing duplicates.'],
            ['title' => 'Fix N+1 query on the project detail page', 'description' => 'Loading a project runs a query per related issue. Eager load the relationships.'],
            ['title' => 'Upgrade to PHP 8.5 and fix deprecations', 'description' => 'Move the codebase to PHP 8.5 and resolve the deprecation warnings in the logs.'],
            ['title' => 'Add rate limiting to the public API', 'description' => 'The public endpoints have no throttling. Add per-client rate limits to prevent abuse.'],
            ['title' => 'Broken image links after storage migration', 'description' => 'Images return 404 after moving to the new storage disk. Update the stored paths.'],
            ['title' => 'Sort issues by priority in the issue list', 'description' => 'Add the ability to order the issue list by priority so urgent items surface first.'],
            ['title' => 'Validation error messages not showing on Firefox', 'description' => 'Inline validation messages are hidden on Firefox. Investigate the markup and styles.'],
            ['title' => 'Session expires too quickly for active users', 'description' => 'Active users are logged out unexpectedly. Review the session lifetime configuration.'],
            ['title' => 'Duplicate notifications sent on order update', 'description' => 'Customers receive two emails for a single update. Guard against the double dispatch.'],
            ['title' => 'Add bulk actions to the issues list', 'description' => 'Allow selecting multiple issues to close or reassign them in a single step.'],
            ['title' => 'Incorrect totals on the billing summary', 'description' => 'The billing summary rounds amounts inconsistently. Align it with the invoice calculation.'],
            ['title' => 'Slow page load on the reports dashboard', 'description' => 'The reports dashboard takes several seconds to load. Profile the queries and add caching.'],
        ];
    }
}
