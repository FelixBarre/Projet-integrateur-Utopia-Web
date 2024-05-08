<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    @include('profile.partials.update-profile-information-form');


    @php
    /*
        foreach (Auth::user()->roles as $role) {
            if ($role->role == "Administrateur") {
                $admin = true;
            }
        }

        foreach($user->roles as $role) {
            if ($role->role == "Administrateur" | $role->role == "Employé") {
                $userNeedAdmin = true;
            }
        }

        if ($admin != true && $userNeedAdmin == true) {
            echo("<p>Vous n'êtes pas autorisés à changer ce profil</p>")
        } elseif (Auth::user()->email == $user->email) {
            include('profile.partials.update-profile-information-form');
        } else {
            include('profile.partials.update-profile-information-form');
        }
    */
    @endphp
</x-app-layout>
