<x-app-layout>
    <div class="p-10">
        <h2 class="text-5xl font-bold text-white mb-10">
            {{ __('Vos conversations') }}
        </h2>

        <a href="{{ route('nouvelleConversation') }}" class="text-2xl inline-block mb-5 bouton">{{ __('+ Nouvelle conversation') }}</a>

        @foreach ($conversations as $conversation)
            @php
                $dernierMessage = App\Models\Message::where('id_conversation', $conversation->id)->orderBy('created_at', 'desc')->first();

                $interlocuteur = null;
                $envoyeurMessage = null;

                if ($dernierMessage->envoyeur->id == $AuthId) {
                    $interlocuteur = $dernierMessage->receveur;
                    $envoyeurMessage = true;
                }
                else {
                    $interlocuteur = $dernierMessage->envoyeur;
                    $envoyeurMessage = false;
                }
            @endphp
            <div class="flex items-center" id="{{ $conversation->id }}">
                <a href="{{ route('conversation', ['id' => $conversation->id]) }}" class="bouton block p-10 mb-5 grow">
                    <h3 class="text-3xl">{{ $interlocuteur->prenom }} {{ $interlocuteur->nom }}</h3>
                    <div class="pt-10 text-xl flex justify-between">
                        <div>
                            @if ($envoyeurMessage)
                                {{ __('Vous') }} :
                            @else
                                {{ $interlocuteur->prenom }} :
                            @endif

                            @if ($dernierMessage->date_heure_supprime)
                                {{ __('Message supprimé') }}
                            @else
                                {{ $dernierMessage->texte }}
                            @endif
                        </div>

                        <div>
                            {{ $dernierMessage->created_at }}
                        </div>
                    </div>
                </a>
                <button class="boutonSupprimerConversation bouton ml-4 mb-5 block">{{ __('Supprimer la conversation') }}</button>
            </div>
        @endforeach
    </div>
</x-app-layout>
