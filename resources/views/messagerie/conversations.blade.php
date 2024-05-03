<x-app-layout>
    <h2 class="text-5xl font-bold text-white mx-9 mb-10">
        {{ __('Vos conversations') }}
    </h2>

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
            <div class="bg-[#178CA4] text-white m-auto border-2 border-solid w-full p-10">
                <h3 class="text-3xl">{{ $interlocuteur->prenom }} {{ $interlocuteur->nom }}</h3>
                <div class="pt-10 text-xl flex justify-between">
                    <div>
                        @if ($envoyeurMessage)
                            {{ __('Vous') }} :
                        @else
                            {{ $interlocuteur->prenom }} {{ $interlocuteur->nom }} :
                        @endif

                        {{ $dernierMessage->texte }}
                    </div>

                    <div>
                        {{ $dernierMessage->created_at }}
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</x-app-layout>
