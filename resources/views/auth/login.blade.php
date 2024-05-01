<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="w-1/2 text-left mt-6">
        <h2 class="text-6xl font-bold text-white mx-9">Banque Utopia - Connexion</h2>
    </div>

    <div class="flex justify-center flex-col flex-wrap items-center mt-44 space-y-5">
        <div>
            <h3 class="text-4xl font-bold text-white">Veuillez vous connecter</h3>
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
</x-guest-layout>
