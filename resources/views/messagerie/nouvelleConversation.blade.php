<x-app-layout>
    <h2 class="text-5xl font-bold text-white mx-9 mb-10">
        {{ __('Nouvelle conversation') }}
    </h2>

    <div class="flex justify-center">
        <form action="{{ route('creerConversation') }}" method="POST" class="border border-solid border-white p-10 text-white text-xl w-3/5">
            @csrf

            <div>
                <datalist id="destinatairesValues">
                    @foreach ($destinataires as $destinataire)
                        <option value="{{ $destinataire->prenom }} {{ $destinataire->nom }} - {{ $destinataire->id }}">{{ $destinataire->prenom }} {{ $destinataire->nom }}</option>
                    @endforeach
                </datalist>
                <label for="destinataire">{{ __('Choisissez un destinataire') }} :</label>
                <input autofocus value="{{old('destinataire')}}" type="text" name="destinataire" id="destinataire" required list="destinatairesValues" class="text-black">
            </div>

            <div class="h-10">
                <label for="message">{{ __('Entrez un message') }} :</label>
            </div>

            <div class="flex justify-center">
                <textarea required name="message" id="message" value="{{old('message')}}" rows="4" maxlength="255" placeholder="Entez votre message ici" class="w-3/5 resize-none text-black"></textarea>
            </div>

            <div class="flex justify-center mt-5">
                <button type="submit" class="bouton">Soumettre</button>
            </div>
        </form>
    </div>
</x-app-layout>
