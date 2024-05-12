<x-app-layout>
    <div class="w-2/4 text-left mt-5">
        <h2 class="text-6xl font-bold text-white mx-9">Comptes utilisateurs</h2>
    </div>

    @if (session('status') === 'non-authorized')
    <div class="flex justify-center w-full"
        x-data="{ show: true }"
        x-show="show"
        x-transition
        x-init="setTimeout(() => show = false, 2000)">
        <div class="flex flex-col justify-center w-96">
            <div class="px-4 py-2 font-bold text-white bg-red-500 rounded-t">Erreur</div>
                <div class="px-4 py-3 text-red-700 bg-red-100 border border-t-0 border-red-400 rounded-b">
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-red-700"
                    >{{ __('Vous n\'êtes pas autorisés à voir ou modifier ce profil.') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="w-full flex justify-center mt-4 mb-4">
        <input type="text" placeholder="Courriel" name="filtreCourriel" id="filtreCourriel" class="w-1/6">
        <input type="hidden" id="action" value="GET">
        <input type="hidden" id="rolesUserLogged" value="{{ $userLogged->roles }}">
        <button class="mr-10" id="boutonFiltreProfiles"><img class="inline-block" src="{{asset('img/funnel.png')}}" alt=""></button>
    </div>

    <div id="divTable">
        <table class="m-auto text-center">
            <thead>
                <tr class="bg-[#178CA4] text-white">
                    <th class="w-1/6 pt-5 pb-5 border-2 border-solid">Prénom</th>
                    <th class="w-1/6 pt-5 pb-5 border-2 border-solid">Nom</th>
                    <th class="w-1/6 pt-5 pb-5 border-2 border-solid">Courriel</th>
                    <th class="w-1/6 pt-5 pb-5 border-2 border-solid">Téléphone</th>
                    <th class="w-1/6 pt-5 pb-5 border-2 border-solid">Consulter</th>
                </tr>
            </thead>
            <tbody id="tbodyProfiles">
                @foreach ($users as $userSpecific)
                <tr class="bg-[#FFFFFF]">
                    <td class="pt-5 pb-5 border-2 border-solid">{{$userSpecific->prenom}}</td>
                    <td class="pt-5 pb-5 border-2 border-solid">{{$userSpecific->nom}}</td>
                    <td class="pt-5 pb-5 border-2 border-solid">{{$userSpecific->email}}</td>
                    <td class="pt-5 pb-5 border-2 border-solid">{{$userSpecific->telephone}}</td>
                    <td class="pt-5 pb-5 border-2 border-solid">
                        <form method="GET" action="{{ route('user.show') }}">
                            <input type="hidden" value="{{$userSpecific->id}}" name="id_user">
                            <button class="bouton" href="">Voir ce profil</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
