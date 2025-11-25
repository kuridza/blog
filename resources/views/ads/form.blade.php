@extends('layouts.app', ['header' => $title])

@section('content')
<div class="text-white py-12 p-5 max-w-7xl mx-auto">

    <form method="POST" enctype="multipart/form-data" class="bg-gray-800 p-5 rounded-lg" action="@isset($ad){{route('ads.update', $ad)}}@else{{route('ads.store')}}@endisset">
        @csrf
        @isset($ad)@method('PUT')<input type="hidden" value="{{$ad->id}}">@endisset

        <x-input-label for="title" :value="__('Naslov')" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $ad->title ?? '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('title')" />

        <x-input-label class="pt-4" for="content" :value="__('Sadrzaj')" />
        <textarea name="content" id="content" class="w-full dark:bg-gray-900 rounded-lg dark:border-gray-700" rows="5" required>{{ old('content', $ad->content ?? '') }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('content')" />

        <x-input-label class="pt-4" for="price" :value="__('Cena')" />
        <x-text-input id="price" name="price" type="text" class="mt-1 block w-full" :value="old('price', $ad->price ?? '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('price')" />

        <x-input-label class="pt-4" for="category_id" :value="__('Kategorija')" />
        <select name="category_id" id="category_id" class="w-full dark:bg-gray-900 rounded-lg dark:border-gray-700" required>
            <option value="">{{ __('-- Izaberi kategoriju --') }}</option>
            @foreach($categories as $category)
                @include('categories.options', ['category' => $category, 'level' => 0, 'selectedCategoryId' => old('category_id', $ad->category_id ?? '') ])
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('category_id')" />

        <div class="mt-4 prose max-w-none">
            <x-input-label class="pt-4" for="is_new" :value="__('Stanje')" />
            <label><input type="radio" name="is_new" value="1" checked required> Novo</label>
            <label><input type="radio" name="is_new" value="0"> Polovno</label>
        </div>

        <div class="mt-4 prose max-w-none">
            @if(isset($ad) && $ad->image)
                <div>
                    <label>{{ __('Trenutna slika') }}</label>
                    <img src="{{ asset('images/' . $ad->image) }}" alt="Current Image" width="100">
                </div>
            @endif
            <x-input-label class="pt-4" for="image" :value="__('Slika')" />
            <input type="file" id="image" name="image" accept="image/*" value="{{ old('image', $ad->image ?? '') }}" class="w-full text-blue-900 dark:bg-gray-900 rounded-lg dark:border-gray-700">
            <x-input-error class="mt-2" :messages="$errors->get('image')" />
        </div>

        <div class="mt-4 prose max-w-none">
            <x-input-label class="pt-4" for="contact_phone" :value="__('Telefon')" required />
            <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full" :value="old('contact_phone', $ad->contact_phone ?? '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('contact_phone')" />
        </div>

        @inject('locations', \App\Services\Locations::class)

        <div class="mt-4 prose max-w-none">
            <x-input-label class="pt-4" for="location" :value="__('Lokacija')" required />
            <select name="location" id="location" class="w-full dark:bg-gray-900 rounded-lg dark:border-gray-700" required>
                <option value="">{{ __('-- Izaberi grad --') }}</option>
                @foreach($locations->getLocations() as $city)
                    <option {{ ( (isset($ad) && $ad->location === $city) || old('location', $ad->location ?? '')) ? 'selected' : '' }}>{{ $city }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('location')" />
        </div>

        <button class="block mt-4 p-2 bg-blue-600">{{ __('Sacuvaj') }}</button>
    </form>

</div>
@endsection
