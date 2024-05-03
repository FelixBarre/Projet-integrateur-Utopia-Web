<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mot de passe') }}
        </h2>
    </x-slot>

    @include('profile.partials.update-password-form')
</x-app-layout>
