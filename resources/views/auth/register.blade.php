<x-guest-layout>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Prenom -->
        <div>
            <x-input-label for="prenom" :value="__('Prénom')" />
            <x-text-input id="prenom" class="block mt-1 w-full" type="text" name="prenom" :value="old('prenom')" required autofocus autocomplete="prenom" />
            <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
        </div>

        <!-- Nom -->
        <div>
            <x-input-label for="nom" :value="__('Nom')" />
            <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom" :value="old('nom')" required autofocus autocomplete="nom" />
            <x-input-error :messages="$errors->get('nom')" class="mt-2" />
        </div>

        <!-- Courriel -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Telephone -->
        <div>
            <x-input-label for="telephone" :value="__('Numéro de téléphone')" />
            <x-text-input id="telephone" class="block mt-1 w-full" type="text" name="telephone" :value="old('telephone')" required autofocus autocomplete="telephone" />
            <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
        </div>

        <!-- Adresse -->
        <div>
            <x-input-label for="noCivique" :value="__('No. civique')" />
            <x-text-input id="noCivique" class="block mt-1 w-full" type="text" name="noCivique" :value="old('noCivique')" required autofocus autocomplete="noCivique" />
            <x-input-error :messages="$errors->get('noCivique')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="rue" :value="__('Rue')" />
            <x-text-input id="rue" class="block mt-1 w-full" type="text" name="rue" :value="old('rue')" required autofocus autocomplete="rue" />
            <x-input-error :messages="$errors->get('rue')" class="mt-2" />
        </div>

        <div class = "mt-4">
            <x-input-label for="ville" :value="__('Ville')"/>
            <select name="ville" id="ville" class="block mt-1 w-full h-30" required>
                @foreach ($villes as $ville)
                    <option value="{{ $ville->id }}">{{$ville->nom}}</option>
                @endforeach
            </select>
        </div>

        <div class = "mt-4">
            <x-input-label for="codePostal" :value="__('Code postal')" />
            <x-text-input id="codePostal" class="block mt-1 w-full" type="text" name="codePostal" :value="old('codePostal')" required autofocus autocomplete="codePostal" />
            <x-input-error :messages="$errors->get('codePostal')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="appt" :value="__('Numéro de porte / d\'appartement')" />
            <x-text-input id="appt" class="block mt-1 w-full" type="text" name="appt" :value="old('appt')" autofocus autocomplete="appt" />
            <x-input-error :messages="$errors->get('appt')" class="mt-2" />
        </div>


        <!-- Role -->
        <div class = "mt-4">
            <x-input-label for="roles" :value="__('Rôle(s)')"/>
            <select name="roles[]" id="roles" class="block mt-1 w-full h-30" required multiple>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{$role->role}}</option>
                @endforeach
            </select>
        </div>

        <p>Le nom d'utilisateur et un mot de passe temporaire sera envoyé à l'utilisateur</p>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Inscription') }}
            </x-primary-button>
        </div>
    </form>
    <div>
        <a href="{{ route('accueil') }}"><button class="bouton">Retour à l'accueil</button></a>
    </div>
</x-guest-layout>
