<x-app-layout>
    @if (session('status') === 'profile-updated')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 2000)"
            class="text-sm text-gray-600"
        >{{ __('Profil sauvegardé.') }}</p>
    @endif

        <div class="flex flex-row"><!-- Premier bloc contenant message, infos et datetime -->

                <div class="w-2/4 text-left">
                    <h2 class="text-6xl font-bold text-white mx-9" id="p">Bonjour !</h2>
                </div>

                <div class="w-2/4 text-right text-white">

                    <div class="flex flex-row items-center space-x-3 place-content-end">
                        <span class="text-2xl font-bold">{{$employe->prenom}} {{ $employe->nom }}</span><span><img class="size-14" src="{{ asset('img/profile.png') }}" alt="profile-img"></span>
                    </div>


                    <div>
                        <p class="mr-20 text-white">{{$date_time }}</p>
                    </div>


                </div>

        </div> <!-- Fin du premier bloc -->

        @if (Session::has('error'))
        <div class="flex justify-center w-full">
            <div role="alert" class="flex flex-col justify-center w-96">
                <div class="px-4 py-2 font-bold text-white bg-red-500 rounded-t">Erreur</div>
                    <div class="px-4 py-3 text-red-700 bg-red-100 border border-t-0 border-red-400 rounded-b">
                        <p>{{ Session::get('error') }}</p>
                    </div>
                </div>
            </div>

        </div>

        @endif

        <div class="flex flex-row"><!--Section filter -->
            <form method="get" id="formSelect" class="w-2/4">
                @csrf
                <p class="my-5 mx-9">
                    <label for="filter" class="text-lg text-white">Trier par </label>
                    <select id="selectValue" name="id_type_Transaction" class="w-64 p-3">
                        <option value="none" id="optionValue">Sélectionner</option>
                        @foreach ($type_transactions as $type_transaction)
                        <option value="{{$type_transaction->id}}" id="optionValue">{{$type_transaction->label}}</option>
                        @endforeach
                    </select>

                </p>


            </form>

            <form method="post" action="{{ route('transactionsFilterDate')}}" class="w-2/4 mr-20 text-right" id="formDate">
                @csrf
                <p class="items-center my-5 mx-9" >
                    <label for="filter" class="text-lg text-white">Filtrer de </label>
                    <input type="date" name="date_debut" id="date_debut" required>
                    <label for="filter" class="text-lg text-white">à</label>
                    <input type="date" name="date_fin" id="date_fin">

                </p>


            </form>

        </div><!-- Fin section filter -->

        <div class="flex flex-row justify-center">
            <form action="{{route('transactionsFilterEmail')}}" method="post">
                @csrf
                <input type="email" placeholder="Entrez l'email du client" name="email" class="w-96" required>
                <button class="bouton">Rechercher</button>
            </form>
        </div>



        <div class="p-10 mb-12 m_auto"><!-- Bloc du tableau des transactions -->
            <table>
                <thead id="transactionTable">
                    <tr class=" bg-[#178CA4] text-white">
                    <th class="w-1/6 p-4 m-auto border-2 border-solid">Id facture</th>
                    <th class="w-1/6 m-auto border-2 border-solid">Nom</th>
                    <th class="w-1/6 m-auto border-2 border-solid">Description</th>
                    <th class="w-1/6 m-auto border-2 border-solid">Montant défini</th>
                    <th class="w-1/6 m-auto border-2 border-solid">Jour du mois</th>
                    <th class="w-1/6 m-auto border-2 border-solid">Fournisseur</th>
                    </tr>
                </thead>
                <tbody id="detailsTransaction">
                    @foreach ($factures as $facture)
                    <td id="transactionEmail" class="m-auto text-center bg-white border-2 border-solid">
                       {{facture->id}}
                    </td>
                    <td id="transactionDate" class="m-auto text-center bg-white border-2 border-solid">{{$facture->nom}}</td>
                    <td id="transactionEtat" class="m-auto text-center border-2 border-solid {{$class_value}}">{{$facture->description}}</td>
                    <td id="transactionEtat" class="m-auto text-center border-2 border-solid {{$class_value}}">{{$facture->montant_defini}}</td>
                    <td id="transactionEtat" class="m-auto text-center border-2 border-solid {{$class_value}}">{{$facture->jour_du_mois}}</td>
                    <td id="transactionEtat" class="m-auto text-center border-2 border-solid {{$class_value}}">{{$facture->fournisseurs->nom}}</td>
                    </tr>
                    @endforeach

                </tbody>
                </table>
        </div><!-- Fin du bloc du tableau des transactions -->


</x-app-layout>
