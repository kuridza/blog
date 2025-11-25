@extends('layouts.app', ['header' => 'Korisnici'])

@section('content')
<div class="text-white p-5 py-12 max-w-7xl mx-auto">
    <div class="flex justify-end">
        <a href="{{ route('profile.create') }}" class="p-2 bg-blue-600" >{{ __('Dodavanje novog korisnika') }}</a>
    </div>
    <div class="mt-4">
        @forelse ($profiles as $profile)
            <div class="flex justify-between p-4 mb-2 bg-gray-800 rounded-lg">
                <a href="{{ route('profile.edit', ['user_id' => $profile->id]) }}" class="hover:underline">
                    <div class="">
                        {{ $profile->name }}
                        <span class="text-gray-400 text-sm">{{ $profile->role }}</span>
                    </div>
                </a>
                <form action="{{ route('profile.destroy', ['user_id' => $profile->id]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <x-danger-button class="p-1 text-red-600" onclick="return confirm('{{ __('Da li ste sigurni?') }}')">{{ __('Obrisi') }}</x-danger-button>
                </form>
            </div>
        @empty
            <div class="text-gray-600">No profiles found.</div>
        @endforelse
    </div>

    <div class="mt-6">
            {{ $profiles->links() }}
    </div>
</div>
@endsection
