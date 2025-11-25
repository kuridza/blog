@extends('layouts.app', ['header' => 'Nov korisnik'])

@section('content')
    <div class="text-white p-5 py-12 max-w-7xl mx-auto">
        <form method="POST" action="{{ route('profile.create') }}" class="bg-gray-800 p-5 rounded-lg">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Ime')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Role -->
            <div class="mt-4">
                <x-input-label for="role" :value="__('Rola')" />
                <select name="role" id="role" class="w-full text-white dark:bg-gray-900 rounded-lg dark:border-gray-700" required>
                    <option>ADMIN</option>
                    <option selected>CUSTOMER</option>
                </select>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full"
                              type="password"
                              name="password"
                              required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Ponovi Password')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                              type="password"
                              name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">

                <x-primary-button class="ms-4">
                    {{ __('Sacuvaj') }}
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection
