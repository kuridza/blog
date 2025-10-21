@extends('layouts.app', ['header' => 'Dashboard'])

@section('content')
<div class="text-white p-5 max-w-7xl mx-auto">

    <div class="flex justify-center items-stretch space-x-4 p-4">
        <div class="flex-1 p-4 rounded-lg bg-gray-800">
            <h3 class="text-xl font-bold mb-2">Total Users</h3>
            <h2 class="text-3xl">{{ $users }}</h2>
            <h3 class="text-xl font-bold mb-2 mt-3">Total Posts</h3>
            <h2 class="text-3xl">{{ $posts }}</h2>
            <h3 class="text-xl font-bold mb-2 mt-3">Posts per User</h3>
            <h2 class="text-3xl">{{ round($posts / $users, 2) }}</h2>
            <h3 class="text-xl font-bold mb-2 mt-3">Total Comments</h3>
            <h2 class="text-3xl">{{ $comments }}</h2>
            <h3 class="text-xl font-bold mb-2 mt-3">Comments per user</h3>
            <h2 class="text-3xl">{{ round($comments / $users, 2) }}</h2>
            <h3 class="text-xl font-bold mb-2 mt-3">Lurkers</h3>
            <h2 class="text-3xl">{{ $lurkers }}</h2>
        </div>

        <div class="flex-1 bg-gray-800 ml-3 p-4 rounded-lg">
            <h3 class="text-xl font-bold mb-2">Top creators:</h3>
            <div class="text-xl">
            @foreach($creators as $user)
                <div>
                    <a class="hover:underline" href="{{ route('posts.index', ['user_id' => $user->id]) }}">{{$user->name}}</a>
                    <span class="text-sm text-gray-400">{{$user->posts_count}}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex-1 bg-gray-800 ml-3 p-4 rounded-lg">
            <h3 class="text-xl font-bold mb-2">Top commentators:</h3>
            <div class="text-xl">
                @foreach($commenters as $user)
                    <div>
                        <a class="hover:underline" href="{{ route('comments.index', ['user_id' => $user->id]) }}">{{$user->name}}</a>
                        <span class="text-sm text-gray-400">{{$user->comments_count}}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex-1 bg-gray-800 ml-3 p-4 rounded-lg">
            <h3 class="text-xl font-bold mb-2">Top posts by comments:</h3>
            <div class="text-xl">
                @foreach($popularPosts as $post)
                    <div>
                        <a class="hover:underline" href="{{ route('posts.show', $post) }}">{{$post->title}}</a>
                        <span class="text-sm text-gray-400">{{$post->comments_count}}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


</div>
@endsection
