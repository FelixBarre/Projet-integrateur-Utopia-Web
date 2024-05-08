<x-app-layout>
    <div class="px-10">
        <h2 class="text-3xl font-bold text-white mb-5">
            {{ $interlocuteur->prenom }} {{ $interlocuteur->nom }}
        </h2>

        <div id="divConversation" class="bg-[#F9F7F0] overflow-y-scroll p-5 h-[70vh] rounded-3xl border border-solid border-[#178CA4] border-4">
            @foreach ($conversation->messages as $message)
                @if ($message->date_heure_supprime)
                    @continue
                @endif
                @php
                    $isEnvoyeur = $message->envoyeur->id == $AuthId;
                @endphp
                <div id="{{ $message->id }}" class="flex items-center {{ $isEnvoyeur ? 'justify-end' : 'justify-start' }}">
                    @if ($isEnvoyeur)
                        <div class="grid gap-4">
                            <img class="h-4 boutonModifierMessage" src="{{asset('img/edit.svg')}}" alt="Modifier" />
                            <img class="h-4 boutonSupprimerMessage" src="{{asset('img/delete.svg')}}" alt="Supprimer" />
                        </div>
                    @endif

                    <div class="w-1/2 m-4">
                        <p class="{{ $isEnvoyeur ? 'text-right' : 'text-left' }}">{{ $message->created_at }}</p>
                        <p class="break-words bg-[{{ $isEnvoyeur ? '#18B7BE' : '#178CA4' }}] p-6 rounded-xl text-white text-justify">{{$message->texte}}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="bg-[#F9F7F0] flex justify-end items-center p-4 rounded-3xl mt-2">
            <img src="" alt="Pièce-jointe" class="w-40" />
            <textarea placeholder="{{ __('Entrez votre message ici') }}" maxlength="255" name="texte" id="texte" class="h-10 w-full resize-none rounded-3xl"></textarea>
            <input type="hidden" id="id_message" />
            <input type="hidden" id="id_envoyeur" value="{{ $AuthId }}" />
            <input type="hidden" id="id_receveur" value="{{ $interlocuteur->id }}" />
            <input type="hidden" id="id_conversation" value="{{ $conversation->id }}" />
            <input type="hidden" id="action" value="POST" />
            <button id="boutonActionMessage" class="bouton select-none">{{ __('Envoyer') }}</button>
        </div>
    </div>
</x-app-layout>
