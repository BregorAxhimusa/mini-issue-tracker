<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function index(Request $request)
    {
        $issues = Issue::with(['project', 'tags'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->input('priority')))
            ->when($request->filled('tag_id'), function ($query) use ($request) {
                $query->whereHas('tags', fn ($tagQuery) => $tagQuery->where('tags.id', $request->input('tag_id')));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $tags = Tag::orderBy('name')->get();

        return view('issues.index', compact('issues', 'tags'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();

        return view('issues.create', compact('projects'));
    }

    public function store(StoreIssueRequest $request)
    {
        $issue = Issue::create($request->validated());

        return redirect()->route('issues.show', $issue);
    }

    public function show(Issue $issue)
    {
        $issue->load(['project', 'tags', 'comments']);

        return view('issues.show', compact('issue'));
    }

    public function edit(Issue $issue)
    {
        $projects = Project::orderBy('name')->get();

        return view('issues.edit', compact('issue', 'projects'));
    }

    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        $issue->update($request->validated());

        return redirect()->route('issues.show', $issue);
    }

    public function destroy(Issue $issue)
    {
        $issue->delete();

        return redirect()->route('issues.index');
    }
}
