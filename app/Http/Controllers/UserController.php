<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(User::orderBy('name')->get(['id', 'name']));
    }

    public function attach(Issue $issue, User $user): JsonResponse
    {
        $issue->assignees()->syncWithoutDetaching([$user->id]);

        return response()->json($this->assignees($issue));
    }

    public function detach(Issue $issue, User $user): JsonResponse
    {
        $issue->assignees()->detach($user->id);

        return response()->json($this->assignees($issue));
    }

    private function assignees(Issue $issue)
    {
        return $issue->assignees()->orderBy('name')->get(['users.id', 'users.name']);
    }
}
