<?php

namespace App\Http\Controllers;

use App\Jobs\PostRisk;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'user_id', 'tag', 'sort', 'direction']);

        $posts = Post::query()
            ->with(['user'])
            ->withFilters($filters)
            ->paginate(8)
            ->withQueryString();

        return view('posts.index', compact('posts', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.form', ['title' => 'Create Post']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'tags' => ['nullable', 'string'],
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'content' => $data['content'],
            'tags' => $data['tags'],
        ]);

        // Queue risk scoring
        PostRisk::dispatch($post->id);

        return redirect()->route('posts.show', $post)->with('status', 'Post created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user']);

        $comments = $post->comments()->paginate(10);

        return view('posts.show', compact('post', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.form', ['post' => $post, 'title' => 'Edit Post']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'tags' => ['nullable', 'string'],
        ]);

        $post->update([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'content' => $data['content'],
            'tags' => $data['tags'],
        ]);

        // Queue risk scoring
        PostRisk::dispatch($post->id);

        return redirect()->route('posts.show', $post)->with('status', 'Post updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('status', 'Post deleted.');
    }

    public function dashboard()
    {
        $posts = Post::query()->count();

        $comments = Comment::query()->count();

        $users = User::query()->count();

        $creators = User::query()
            ->withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->where('posts_count', '>', 0)
            ->limit(5)
            ->get();

        $lurkers = User::query()
            ->doesntHave('posts')->doesntHave('comments')->count();

        $commenters = User::query()
            ->withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->where('comments_count', '>', 0)
            ->limit(5)
            ->get();

        $popularPosts = Post::query()
            ->withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->where('comments_count', '=', 2)
            ->limit(5)
            ->get()
        ;


//        dd($popularPosts->toRawSql(), $popularPosts->get()->toArray());

        return view('posts.dashboard', compact(
            'posts',
            'comments',
            'users',
            'creators',
            'lurkers',
            'commenters',
            'popularPosts',
        ));
    }
}
