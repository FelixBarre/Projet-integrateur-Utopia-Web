<x-app-layout>
    <h2 class="font-semibold text-3xl text-white leading-tight">
    {{ __('Nouveau rapport') }}
    </h2>

    <form method="post" action="{{ route('creationRapport') }}" class="flex justify-center p-10" id="nouveauRapport">
        @csrf

        <div class="w-3/4">
            <div class="grid grid-cols-2">
                <label for="titre">{{ __('Titre') }}</label>
                <input type="text" name="titre" id="titre" value="{{old('titre')}}">

                <label for="description">{{ __('Description') }}</label>
                <input type="text" name="description" id="description" value="{{old('description')}}">

                <label for="date_debut">{{ __('Date de début') }}</label>
                <input type="date" name="date_debut" id="date_debut" value="{{old('date_debut')}}">

                <label for="date_fin">{{ __('Date de fin') }}</label>
                <input type="date" name="date_fin" id="date_fin">

                <label for="user">{{ __('Utilisateur concerné') }}</label>
                <select name="user" id="user">
                    <option value="0" selected>Tous</option>
                    @foreach ($utilisateurs as $utilisateur)
                        <option value="{{ $utilisateur->id }}" {{ (old("user") == $utilisateur->id ? "selected":"") }}>{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</option>
                    @endforeach
                </select>

                <label for="type_transactions">Types de transactions à ajouter</label>
                <select name="type_transactions[]" id="type_transactions" multiple>
                    @foreach ($type_transactions as $type_transactions)
                        <option value="{{ $type_transactions->id }}">{{ $type_transactions->label }}</option>
                    @endforeach
                </select>

                <label for="type_demandes">Types de demandes à ajouter</label>
                <select name="type_demandes[]" id="type_demandes" multiple>
                    @foreach ($type_demandes as $type_demandes)
                        <option value="{{ $type_demandes->id }}">{{ $type_demandes->label }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bouton">
                {{ __('Créer le rapport') }}
            </button>
        </div>
    </form>
</x-app-layout>
