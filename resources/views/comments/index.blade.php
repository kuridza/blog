@extends('layouts.app', ['header' => 'Comments by '. $comments->first()->user->name])

@section('content')
    <div class="text-white p-5 max-w-7xl mx-auto">
        @if($comments->count())
            @foreach($comments as $comment)
                <div class="p-6 mt-3 bg-gray-800">
                    <h1>Comment: </h1>
                    <p>{{ $comment->body }}</p>
                    <h2>On post:</h2>
                    <div>
                        <a href="{{ route('posts.show', $comment->post->id) }}" class="hover:underline">
                            {{ \Illuminate\Support\Str::limit($comment->post->title, 60) }}
                        </a>
                    </div>
                    <div>{{ $comment->created_at->diffForHumans() }}</div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center">
                {{ $comments->withQueryString()->links() }}
            </div>
        @else
            <div class="alert alert-info">No comments found.</div>
        @endif
    </div>
@endsection
