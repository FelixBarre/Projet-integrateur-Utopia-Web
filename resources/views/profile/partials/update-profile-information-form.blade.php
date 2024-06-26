<?php
    use App\Models\Ville;
    $villes = Ville::All()
?>

<div class="w-1/2 text-left mt-6">
    <h2 class="text-5xl font-bold text-white mx-9">Mon profil</h2>
</div>

<div class="flex justify-center flex-col flex-wrap items-center mt-22 space-y-5">
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <div>
        <h3 class="text-4xl font-bold text-white">Édition du profil</h3>
    </div>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-10 w-1/2">
        @csrf
        @method('patch')
        <div class="flex space-x-5 justify-center">
            <!-- Prenom -->
            <div>
                <x-text-input id="prenom" class="block mt-1 w-full text-center" type="text" name="prenom" :value="old('prenom', $user->prenom)" required autofocus autocomplete="prenom"/>
            </div>


            <!-- Nom -->
            <div>
                <x-text-input id="nom" class="block mt-1 w-full text-center" type="text" name="nom" :value="old('nom', $user->nom)" required autofocus autocomplete="nom" placeholder="Nom"/>
            </div>
        </div>

        <!-- Telephone -->
        <div class="flex justify-center">
            <x-text-input id="telephone" class="block mt-1 w-2/5 text-center" type="text" name="telephone" :value="old('telephone', $user->telephone)" required autofocus autocomplete="telephone" />
        </div>

        <!-- Adresse -->
        <div class="flex flex-wrap justify-center space-x-5 max-w-full">
            <div class="w-1/6">
                <x-text-input id="appt" class="block mt-1 w-full" type="text" name="appt" :value="old('appt', $user->no_porte)" autofocus autocomplete="appt" placeholder="No. Appt" />
            </div>

            <div class="w-1/6">
                <x-text-input id="noCivique" class="block mt-1 w-full" type="text" name="noCivique" :value="old('noCivique', $user->no_civique)" required autofocus autocomplete="noCivique" />
            </div>

            <div class="w-1/5">
                <x-text-input id="rue" class="block mt-1 w-full" type="text" name="rue" :value="old('rue', $user->rue)" required autofocus autocomplete="rue" />
            </div>

            <div class="flex justify-center space-x-5 w-full">
                <div class = "mt-4 w-1/3">
                    <select name="ville" id="ville" class="block mt-1 w-full h-30" required>
                        @foreach ($villes as $ville)
                            <option value="{{ $ville->id }}"
                            @if($ville->id == $user->id_ville)
                                @selected(true)
                            @endif
                            >{{$ville->nom}}</option>
                        @endforeach
                    </select>
                </div>

                <div class = "mt-4 1/5">
                    <x-text-input id="codePostal" class="block mt-1 w-full" type="text" name="codePostal" :value="old('codePostal', $user->code_postal)" required autofocus autocomplete="codePostal" />
                </div>
            </div>
        </div>


        <div class="flex justify-center items-center space-x-4">
            <button class="bouton">Sauvegarder</button>
        </div>

        <input type="hidden" name="id_user" id="id_user" value="{{$user->id}}">
    </form>
</div>
