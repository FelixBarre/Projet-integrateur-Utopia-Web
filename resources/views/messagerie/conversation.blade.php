<x-app-layout>
    <div class="px-10 h-[70vh]">
        <h2 class="text-3xl font-bold text-white mb-5">
            {{ $interlocuteur->prenom }} {{ $interlocuteur->nom }}
        </h2>

        <div class="bg-[#F9F7F0] overflow-y-scroll p-5 h-full rounded-3xl border border-solid border-[#178CA4] border-4">
            @foreach ($messages as $message)
                @php
                    $isEnvoyeur = $message->envoyeur->id == $AuthId;
                @endphp
                <div class="flex {{ $isEnvoyeur ? 'justify-end' : 'justify-start' }}">
                    <div class="w-1/2 m-4">
                        <p>{{ $message->created_at }}</p>
                        <p class="bg-[{{ $isEnvoyeur ? '#18B7BE' : '#178CA4' }}] p-6 rounded-xl text-white text-justify">{{$message->texte}}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="bg-[#F9F7F0] flex justify-end items-center p-4 rounded-3xl mt-2">
            <img src="" alt="Pièce-jointe" class="w-40" />
            <textarea maxlength="255" name="message" id="message" class="h-10 w-full resize-none rounded-3xl"></textarea>
            <div href="" class="bouton select-none">{{ __('Envoyer') }}</div>
        </div>
    </div>
</x-app-layout>
