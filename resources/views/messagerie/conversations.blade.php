<x-app-layout>
    <div class="p-10">
        <h2 class="text-5xl font-bold text-white mb-10">
            {{ __('Vos conversations') }}
        </h2>

        <a href="{{ route('nouvelleConversation') }}" class="text-2xl inline-block mb-5 bouton">{{ __('+ Nouvelle conversation') }}</a>

        @foreach ($conversations as $conversation)
            @php
                $tousMessages = $conversation->messages()->orderBy('created_at', 'desc')->get();
                $messagesNonSupprimes = $conversation->messages()->orderBy('created_at', 'desc')->whereNull('date_heure_supprime')->get();

                $dernierMessage = null;

                if (count($messagesNonSupprimes) > 0) {
                    $dernierMessage = $messagesNonSupprimes->first();
                }
                else {
                    $dernierMessage = $tousMessages->first();
                }

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
            <a href="{{ route('conversation', ['id' => $conversation->id]) }}" class="bouton block p-10 mb-5">
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
        @endforeach
    </div>
</x-app-layout>
