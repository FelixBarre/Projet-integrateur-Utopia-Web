<x-app-layout>
    <div class="w-1/2 text-left mt-6">
        <h2 class="text-5xl font-bold text-white mx-9">Consultation de compte</h2>
    </div>

    <div class="flex justify-center flex-col flex-wrap items-center mt-44 space-y-5">
        <div>
            <h3 class="text-4xl font-bold text-white">Consultation du profil</h3>
        </div>

        <div>
            <p class="text-2xl font-bold text-white">{{ $user->prenom }} {{ $user->nom }}</p>
        </div>

        <div class="flex">
            <div class="w-1/2">
                <p>{{ $user->email }}</p>
            </div>
            <div class="w-1/2">
                <p>{{ $user->telephone }}</p>
            </div>
        </div>

        <div>
            @if (isset($user->no_porte))
                <p>{{ $user->no_porte}}-{{ $user->no_civique}}, rue {{ $user->rue }}</p>
                <p>{{ $ville->nom }}, QC, {{ $user->code_postal }}</p>
            @else
                <p>{{ $user->no_civique}}, rue {{ $user->rue }}</p>
                <p>{{ $ville->nom }}, QC, {{ $user->code_postal }}</p>
            @endif
        </div>


        <form method="POST" action="{{ route('login') }}" class="flex flex-col items-center space-y-5  w-1/5">
            @csrf

            <!-- Email Address -->
            <div class="w-full">
                <x-text-input id="email" class="block mt-1 w-full"
                                type="email" name="email"
                                :value="old('email')"
                                required autofocus autocomplete="email"
                                placeholder="Courriel" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4 w-full">
                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password"
                                placeholder="Mot de passe" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <button class="bouton">
                    {{ __('Se connecter') }}
                </button>
            <div>

            @if (Route::has('password.request'))
                <div  class="mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié?') }}
                    </a>
                </div>
            @endif
        </form>
    </div>
</x-app-layout>
