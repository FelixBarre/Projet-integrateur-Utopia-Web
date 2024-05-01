<x-app-layout>
    <div class="w-1/2 text-left mt-6">
        <h2 class="text-5xl font-bold text-white mx-9">Consultation de compte</h2>
    </div>

    <div class="flex justify-center flex-col flex-wrap items-center mt-44 space-y-10">
        <div>
            <h3 class="text-4xl font-bold text-white mb-10">Consultation du profil</h3>
        </div>

        <div>
            <p class="text-2xl font-bold text-white">{{ $user->prenom }} {{ $user->nom }}</p>
        </div>

        <div class="flex w-1/4">
            <div class="w-1/2 flex justify-center">
                <p class="font-bold text-white">{{ $user->email }}</p>
            </div>
            <div class="w-1/2 flex justify-center">
                <p class="font-bold text-white">{{ $user->telephone }}</p>
            </div>
        </div>

        <div class="text-center text-white">
            @if (isset($user->no_porte))
                <p class="w-full">{{ $user->no_porte}}-{{ $user->no_civique}}, rue {{ $user->rue }}</p>
                <p class="w-full">{{ $ville->nom }}, QC, {{ $user->code_postal }}</p>
            @else
                <p class="w-full">{{ $user->no_civique}}, rue {{ $user->rue }}</p>
                <p class="w-full">{{ $ville->nom }}, QC, {{ $user->code_postal }}</p>
            @endif
        </div>

        <div class="flex align-center space-x-4">
            <div>
                <a href="{{ route('profile.edit') }}"><button class="bouton">Modifier</button></a>
            </div>
            <div>
                <a href="{{ route('accueil') }}"><button class="bouton">Retour à l'accueil</button></a>
            </div>
        </div>
    </div>
</x-app-layout>
