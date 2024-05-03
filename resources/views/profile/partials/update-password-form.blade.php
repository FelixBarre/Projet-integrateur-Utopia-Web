<div class="flex justify-center flex-col flex-wrap items-center mt-22 space-y-5">
    <div>
        <h3 class="text-4xl font-bold text-white">Modification de mot de passe</h3>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6 w-1/2 flex flex-wrap justify-center">
        @csrf
        @method('put')

        <div class="flex items-center text-right space-x-5 w-full">
            <x-input-label for="update_password_current_password" :value="__('Ancien mot de passe: ')" class="w-1/2 text-white text-xl"/>
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-1/3" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="flex items-center text-right space-x-5 w-full">
            <x-input-label for="update_password_password" :value="__('Nouveau mot de passe: ')" class="w-1/2 text-white text-xl"/>
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-1/3" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center text-right space-x-5 w-full">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmer le mot de passe')" class="w-1/2 text-white text-xl"/>
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-1/3" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button class="bouton">Sauvegarder</button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Sauvegardé.') }}</p>
            @endif
        </div>
    </form>
</div>
