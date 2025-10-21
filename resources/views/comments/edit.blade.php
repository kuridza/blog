@extends('layouts.app', ['header' => 'Edit comment'])

@section('content')
<div class="text-white p-5 max-w-7xl mx-auto">
    <form method="POST" action="{{route('comments.update', $comment)}}">
        @csrf
        @method('PUT')
        <label class="block">Comment</label>
        <textarea name="body" class="w-full text-blue-900" rows="5" required>{{$comment->body}}</textarea>
        <button class="block mt-4 p-2 bg-blue-600">Edit comment</button>
    </form>
</div>
@endsection
