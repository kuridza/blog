@extends('layouts.app', ['header' => $post->title])

@section('content')
<div class="text-white p-5 max-w-7xl mx-auto">
    <div class="p-6 bg-gray-800">
        <div class="flex justify-between items-start">
            <p>Posted {{ $post->user->name }} on {{ $post->created_at->toDayDateTimeString() }}</p>
            <div class="space-x-2 flex justify-end">
                @can('update', $post)
                    <a href="{{ route('posts.edit', $post) }}" class="p-1">Edit</a>
                @endcan
                @can('delete', $post)
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="p-1 text-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                @endcan
            </div>
        </div>
        <div class="mt-4 prose max-w-none">
            {!! nl2br(e($post->content)) !!}
        </div>
        <div class="mt-4 flex items-center justify-between">
            <div class="text-sm">
                @foreach (@explode(',', $post->tags) as $tag)
                    <a href="{{ route('posts.index', ['tag' => $tag]) }}" class="inline-block px-2 bg-amber-200 text-blue-900">{{ $tag }}</a>
                @endforeach
            </div>
            <div class="text-sm">
                @if($post->risk_level)
                    <span class="px-2 py-1 rounded {{ $post->risk_level === 'high' ? 'bg-red-100 text-red-700' : ($post->risk_level === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                    Risk: {{ $post->risk_level }} ({{ $post->risk_score }})
                </span>
                @endif
            </div>
        </div>
    </div>

    <div class="p-6">
        <h2 class="text-xl font-semibold mb-4">{{ $comments->total() }} comments</h2>
        @auth
            <form method="POST" action="{{ route('comments.store', $post) }}" class="space-y-3 mb-6">
                @csrf
                <textarea name="body" rows="4" class="w-full border px-3 py-2 text-blue-900 bg-blue-100" required>{{ old('body') }}</textarea>
                @error('body') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    <button class="p-2 bg-blue-600">Add Comment</button>
            </form>
        @endauth

        <div class="space-y-4">
            @forelse ($comments as $comment)
                <div class="p-5 mt-3 bg-gray-800 @if($comment->flagged) bg-neutral-700 @endif">
                    <div class="text-sm">{{ $comment->user->name }} comented {{ $comment->created_at->diffForHumans() }}</div>
                    <div class="space-x-2 flex justify-end">
                        @if(!$comment->flagged)
                            <form action="{{ route('comments.flag', $comment) }}" method="POST" class="inline">
                                @csrf
                                <button class="p-2 text-yellow-700">Flag</button>
                            </form>
                        @else
                            @can('moderate', $comment)
                                <form action="{{ route('comments.unflag', $comment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="p-2 text-green-700">Unflag</button>
                                </form>
                            @endcan
                        @endif
                        @can('update', $comment)
                            <a href="{{ route('comments.edit', $comment) }}" class="p-2">Edit</a>
                        @endcan
                        @can('delete', $comment)
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 text-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        @endcan
                    </div>
                    <div class="mt-1">{{ $comment->body }}</div>
                </div>
            @empty
                <div class="text-gray-600">No comments yet.</div>
            @endforelse
            <div class="mt-3">
                {{ $comments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
