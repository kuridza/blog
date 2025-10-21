@extends('layouts.app', ['header' => $title])

@section('content')
<div class="text-white p-5 max-w-7xl mx-auto">

    <form method="POST" class="bg-gray-800 p-5" action="@isset($post){{route('posts.update', $post)}}@else{{route('posts.store')}}@endisset">
        @csrf
        @isset($post)@method('PUT')<input type="hidden" value="{{$post->id}}">@endisset
        <label class="block">Title</label>
        <input name="title" value="@isset($post){{$post->title}}@endisset" class="w-full text-blue-900 bg-blue-100" required>
        @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror

        <label class="block">Content</label>
        <textarea name="content" class="w-full text-blue-900 bg-blue-100" rows="5" required>@isset($post){{$post->content}}@endisset</textarea>
        @error('content') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror

        <label class="block">Tags (comma separated)</label>
        <textarea name="tags" class="w-full text-blue-900 bg-blue-100" rows="3">@isset($post){{$post->tags}}@endisset</textarea>

        <button class="block mt-4 p-2 bg-blue-600">{{$title}}</button>
    </form>

</div>
@endsection
