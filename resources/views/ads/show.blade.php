@extends('layouts.app', ['header' => __('Pregled oglasa') . ': ' . $ad->title])

@section('content')
    <div class="flex text-white py-12 p-5 max-w-7xl mx-auto">
        @include('ads.sidebar', ['categories' => $categories, 'activeCategory' => $ad->category->id])

        <div class="flex-1 p-6 bg-gray-800 rounded-lg max-w">
            <div class="text-blue-500">{{ $categoryPath }}</div>
            <div class="flex justify-between items-start">
                <div class="text-xl">
                    {{ $ad->title }}
                </div>
                @can('update', $ad)
                    <a href="{{ route('ads.edit', $ad) }}" class="p-1">{{ __('Izmeni') }}</a>
                @endcan
            </div>
            <div class="flex justify-between items-start">
                <span>{{ __('Postavio/la') }} {{ $ad->user->name }}</span>
                <span class="text-red-600">{{ $ad->price }} RSD ({{ $ad->is_new ? 'Novo' : 'Polovno' }})</span>
                <span class="text-gray-400">{{ $ad->created_at->format('d/m/Y') }}</span>
                @can('delete', $ad)
                    <form action="{{ route('ads.destroy', $ad) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="p-1 text-red-600"
                                onclick="return confirm('{{ __('Da li ste sigurni?') }}')">{{ __('Obrisi') }}</button>
                    </form>
                @endcan
            </div>
            <div>{{ __('Kontakt') }}: {{ $ad->contact_phone }}</div>
            <div>{{ __('Lokacija') }}: {{ $ad->location }}</div>
            <img class="items-center mt-4" src="/images/{{ $ad->image }}" width="300">
            <div class="mt-4 prose max-w-none">
                {!! nl2br(e($ad->content)) !!}
            </div>
        </div>
    </div>
@endsection
