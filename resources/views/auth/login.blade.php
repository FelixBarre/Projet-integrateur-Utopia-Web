<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mt-6 text-center">
        <img src="{{asset('img/letter-u-lg.png')}}" alt="logo-utopia" class="inline-block w-32 p-1">
        <h1 class="justify-center mx-5 font-bold text-white text-7xl">Banque Utopia</h1

    </div>

    <div class="w-1/3 p-2 m-auto mt-20 border-4 border-white border-double rounded-3xl">

        <div class="flex flex-col flex-wrap items-center justify-center mt-5 space-y-5">

            <h2 class="text-6xl font-bold text-white mx-9 ">Connexion</h2>
            <div>
                <h3 class="text-2xl font-bold text-white">Veuillez vous connecter</h3>
            </div>


            <form method="POST" action="{{ route('login') }}" class="flex flex-col items-center w-2/3 space-y-5">
                @csrf

                <!-- Email Address -->
                <div class="w-full">
                    <x-text-input id="email" class="block w-full mt-1"
                                    type="email" name="email"
                                    :value="old('email')"
                                    required autofocus autocomplete="email"
                                    placeholder="Courriel" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="w-full mt-4">
                    <x-text-input id="password" class="block w-full mt-1"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password"
                                    placeholder="Mot de passe" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <button class="bouton" type="submit">
                        {{ __('Se connecter') }}
                    </button>
                <div>

                @if (Route::has('password.request'))
                    <div  class="mt-4">
                        <a class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Mot de passe oublié?') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>

    </div>


</x-guest-layout>
