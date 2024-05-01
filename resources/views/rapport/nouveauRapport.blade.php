<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl leading-tight">
    {{ __('Nouveau rapport') }}
    </h2>
    </x-slot>

    <form method="post" action="{{ route('creationRapport') }}" class="flex justify-center p-10">
        @csrf

        <div class="w-3/4">
            <div class="grid grid-cols-2">
                <label for="titre">{{ __('Titre') }}</label>
                <input type="text" name="titre" id="titre">

                <label for="description">{{ __('Description') }}</label>
                <input type="text" name="description" id="description">

                <label for="date_debut">{{ __('Date de début') }}</label>
                <input type="date" name="date_debut" id="date_debut">

                <label for="date_fin">{{ __('Date de fin') }}</label>
                <input type="date" name="date_fin" id="date_fin">
            </div>

            <button type="submit" class="bouton">
                {{ __('Créer le rapport') }}
            </button>
        </div>
    </form>
</x-app-layout>
