<x-app-layout>

    <div class="w-2/4 text-left mt-5">
        <h2 class="text-6xl font-bold text-white mx-9">Demandes de prêts</h2>
    </div>

    <div class="m-auto w-11/12 text-right">
        <form method="GET" action="{{ route('demandesPretFiltre') }}">
            <select class="mr-16 w-56" name="filtre_demandePret" id="filtre_demandePret">
                <option value="all">Toutes</option>
                @foreach ($etats as $etat)
                <option value="{{$etat->id}}">{{$etat->label}}</option>
                @endforeach
            </select>

            <button class="mr-10" href=""><img class="inline-block" src="{{asset('img/funnel.png')}}" alt=""></button>
        </form>

    </div>

    <div>
        <table id="table_demandePret" class=" m-auto text-center">
            <thead>
                <tr class="bg-[#178CA4] text-white">
                    <th class="pt-5 pb-5 border-2 border-solid">Prenom</th>
                    <th class="pt-5 pb-5 border-2 border-solid">Nom</th>
                    <th class="pt-5 pb-5 border-2 border-solid">Courriel</th>
                    <th class="pt-5 pb-5 border-2 border-solid">Date de la demande</th>
                    <th class="pt-5 pb-5 border-2 border-solid">Raison</th>
                    <th class="pt-5 pb-5 border-2 border-solid">Montant</th>
                    <th class="pt-5 pb-5 border-2 border-solid">État</th>
                    <th class="pt-5 pb-5 border-2 border-solid">Accéder à la demande</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($demandes as $demande)
                <tr class="bg-[#FFFFFF]">
                    <td class="pt-5 pb-5 border-2 border-solid">{{$demande->user_demande->prenom}}</td>
                    <td class="pt-5 pb-5 border-2 border-solid">{{$demande->user_demande->nom}}</td>
                    <td class="pt-5 pb-5 border-2 border-solid">{{$demande->user_demande->email}}</td>
                    <td class="pt-5 pb-5 border-2 border-solid">{{$demande->date_demande}}</td>
                    <td class="pt-5 pb-5 border-2 border-solid">{{$demande->raison}}</td>
                    <td class="pt-5 pb-5 border-2 border-solid">{{$demande->montant}}$</td>
                    <td class="pt-5 pb-5 border-2 border-solid">{{$demande->etat_demande->label}}</td>
                    <td class="pt-5 pb-5 border-2 border-solid">
                        <form method="GET" action="{{ route('demandePret') }}">
                            <input type="hidden" value="{{$demande->id}}" name="id_demande">
                            <button class="bouton" href="">Voir</button>
                        </form>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

</x-app-layout>
