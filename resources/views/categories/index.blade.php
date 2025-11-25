@extends('layouts.app', ['header' => __('Kategorije')])

@section('content')
    <div class="text-white p-5 py-12 max-w-7xl mx-auto">

        <div class="p-6 bg-gray-800 rounded-lg">
            <a href="{{ route('categories.create') }}" class="mt-4 p-2 bg-blue-600">{{ __('Dodaj novu kategoriju') }}</a>
            <h2 class="mt-4">{{ __('Izmena kategorija') }}:</h2>
            <div class="category-tree mt-4">
                <ul>
                    @foreach($categories as $category)
                        {{-- Start the recursive rendering for each top-level category --}}
                        @include('categories.tree', ['category' => $category, 'route' => route('categories.edit', $category)])
                    @endforeach
                </ul>
            </div>
        </div>

        @if(! $categories->count())
            <div class="alert alert-info">{{ __('Nema kategorija') }}</div>
        @endif
    </div>
@endsection
