<x-app-layout>
    <div class="p-10">
        <h2 class="text-5xl font-bold text-white mb-10">
            {{ __('Vos conversations') }}
        </h2>

        <a href="{{ route('nouvelleConversation') }}" class="text-2xl inline-block mb-5 bouton">{{ __('+ Nouvelle conversation') }}</a>

        @foreach ($conversations as $conversation)
            @if (isset($conversation->messages[0]))
                @php
                    $dernierMessage = $conversation->messages()->orderBy('created_at', 'desc')->first();

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
                <a href="" class="bouton block p-10 mb-5">
                    <h3 class="text-3xl">{{ $interlocuteur->prenom }} {{ $interlocuteur->nom }}</h3>
                    <div class="pt-10 text-xl flex justify-between">
                        <div>
                            @if ($envoyeurMessage)
                                {{ __('Vous') }} :
                            @else
                                {{ $interlocuteur->prenom }} :
                            @endif

                            {{ $dernierMessage->texte }}
                        </div>

                        <div>
                            {{ $dernierMessage->created_at }}
                        </div>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</x-app-layout>
