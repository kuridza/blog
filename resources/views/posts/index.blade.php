@extends('layouts.app', ['header' => 'Posts' . (isset($filters['user_id']) ? ' by ' . $posts->first()->user->name : '')])

@section('content')
<div class="text-white p-5 max-w-7xl mx-auto">
    <form method="GET" action="{{ route('posts.index') }}">
       <select name="sort" class="px-3 py-2 text-blue-900 w-full" onchange="javascript:this.form.submit()">
            <option value="desc" @selected(($filters['sort'] ?? '') === 'desc')>Latest</option>
            <option value="asc" @selected(($filters['sort'] ?? '') === 'asc')>Oldest</option>
        </select>
    </form>

    @forelse ($posts as $post)
        <div class="p-6 mt-3 bg-gray-800">
            <a href="{{ route('posts.show', $post) }}" class="hover:underline">
                <div class="flex justify-between items-start">
                    {{ $post->title }}
                    <p>Posted {{ $post->user->name }} on {{ $post->created_at }} @if ($post->risk_level) <br> {{ $post->risk_level }} risk @endif</p>
                </div>
            </a>
            <p>{{ \Illuminate\Support\Str::limit($post->content, 100) }}</p>
            <div class="mt-4 ">
                <div class="text-sm">
                    @foreach (@explode(',', $post->tags) as $tag)
                        <a href="{{ route('posts.index', ['tag' => $tag]) }}" class="inline-block px-2 bg-amber-200 text-blue-900">{{ $tag }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    @empty
        <div class="text-gray-600">No posts found.</div>
    @endforelse

    <div class="mt-6">
            {{ $posts->links() }}
    </div>
</div>
@endsection
