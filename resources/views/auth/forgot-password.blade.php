<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="w-1/2 text-left mt-6">
        <h2 class="text-6xl font-bold text-white mx-9">Banque Utopia - Réinitialisation de mot de passe</h2>
    </div>

    <div class="flex justify-center flex-col flex-wrap items-center mt-44 space-y-5">
        <div>
            <p class="font-bold text-white">Vous avez oublié votre mot de passe? Pas de problème!</p>
            <p class="font-bold text-white">Inscrivez votre adresse courriel et nous vous enverrons un lien de réinitialisation de mot de passe pour en choisir un nouveau.</p>
        </div>


        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col items-center space-y-5  w-1/5">
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

            <div class="flex items-center justify-end mt-4">
                <button class="bouton">
                    Envoi du lien de réinitialisation
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
