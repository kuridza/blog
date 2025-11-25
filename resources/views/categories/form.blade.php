@extends('layouts.app', ['header' => $title])

@section('content')
<div class="text-white py-12 p-5 max-w-7xl mx-auto">
    <form method="POST" class="bg-gray-800 p-5 rounded-lg" action="@isset($category){{route('categories.update', $category)}}@else{{route('categories.store')}}@endisset">
        @csrf
        @isset($category)@method('PUT')<input type="hidden" value="{{$category->id}}">@endisset

        <x-input-label for="name" :value="__('Naziv')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $category->name ?? '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />

        @if(isset($category))
            <x-input-label for="child" class="mt-4" :value="__('Podkategorije')" />
            @foreach($category->children as $child)
                @can('update', $category)
                    <a href="{{ route('categories.edit', $child) }}" class="block mt-1 hover:underline">{{ $child->name }}</a>
                @endcan
            @endforeach

            <x-input-label for="child" class="mt-4" :value="__('Dodaj podkategoriju')" />
            <x-text-input id="child" name="child" type="text" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('child')" />
        @endif

        <button class="block mt-4 p-2 bg-blue-600">{{ __('Sacuvaj') }}</button>

    </form>
</div>
@endsection
