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

    <div class="w-1/2 text-left mt-6">
        <h2 class="text-5xl font-bold text-white mx-9">Inscription d'usager</h2>
    </div>

    <div class="flex justify-center flex-col flex-wrap items-center mt-44 space-y-5">
        <div>
            <h3 class="text-4xl font-bold text-white">Informations d'utilisateur</h3>
        </div>

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5 w-1/2 flex flex-col items-center">
            @csrf

            <div class="flex space-x-5 justify-center">
                <!-- Prenom -->
                <div>
                    <x-text-input id="prenom" class="block mt-1 w-full" type="text" name="prenom" :value="old('prenom')" required autofocus autocomplete="prenom" placeholder="Prénom"/>
                </div>

                <!-- Nom -->
                <div>
                    <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom" :value="old('nom')" required autofocus autocomplete="nom" placeholder="Nom"/>
                </div>
            </div>

            <div class="flex space-x-5 justify-center">
                <!-- Courriel -->
                <div class="w-3/5">
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="Courriel" />
                </div>

                <!-- Telephone -->
                <div>
                    <x-text-input id="telephone" class="block mt-1 w-full" type="text" name="telephone" :value="old('telephone')" required autofocus autocomplete="telephone" placeholder="Téléphone" />
                </div>
            </div>

            <div class="flex space-x-5 justify-center w-3/5">
                <div class="w-1/2 flex space-x-5">
                    <!-- Adresse -->
                    <div class="w-1/2">
                        <x-text-input id="appt" class="block mt-1 w-full" type="text" name="appt" :value="old('appt')" autofocus autocomplete="appt" placeholder="No. Porte"/>
                    </div>

                    <div class="w-3/5">
                        <x-text-input id="noCivique" class="block mt-1 w-full" type="text" name="noCivique" :value="old('noCivique')" required autofocus autocomplete="noCivique" placeholder="No. civique"/>
                    </div>
                </div>

                <div class="w-2/3">
                    <x-text-input id="rue" class="block mt-1 w-full" type="text" name="rue" :value="old('rue')" required autofocus autocomplete="rue" placeholder="Rue"/>
                </div>
            </div>

            <div class="flex space-x-5 justify-center">
                <div class="w-2/5">
                    <select name="ville" id="ville" class="block mt-1 w-full h-30" required>
                        @foreach ($villes as $ville)
                            <option value="{{ $ville->id }}">{{$ville->nom}}</option>
                        @endforeach
                    </select>
                </div>


                <div class="w-1/5">
                    <x-text-input id="codePostal" class="block mt-1 w-full" type="text" name="codePostal" :value="old('codePostal')" required autofocus autocomplete="codePostal" placeholder="Code postal"/>
                </div>
            </div>


            <!-- Role -->
            <?php $admin = false ?>
            @foreach (Auth::user()->roles as $roleUser)
                @if($roleUser['role'] == "Administrateur")
                    @php($admin = true)
                @endif
            @endforeach

            <?php if($admin == true) { ?>
                <div>
                    <x-input-label for="roles" :value="__('Rôle(s)')"/>
                    <select name="roles[]" id="roles" class="block mt-1 w-full h-30" required multiple>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{$role->role}}</option>
                        @endforeach
                    </select>
                </div>
            <?php } else { ?>
                <select name="roles[]" id="roles" hidden>
                    <option selected value="3"></option>
                </select>
            <?php } ?>

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
    </div>
</x-guest-layout>
