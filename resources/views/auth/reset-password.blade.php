<x-guest-layout>
    <div class="flex justify-center items-center h-screen">
        <form method="POST" action="{{ route('password.store') }}" class="w-1/6">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="Courriel" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Mot de passe"/>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" placeholder="Confirmer le mot de passe"/>
            </div>

            <div class="flex items-center justify-center mt-4">
                <button class="bouton">
                    Réinitialiser le mot de passe
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
