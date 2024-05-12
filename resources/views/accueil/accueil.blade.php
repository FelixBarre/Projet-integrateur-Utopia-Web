<x-app-layout>
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


        @if (session('status') === 'profile-updated')
        <div class="flex justify-center w-full"
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 2000)">
            <div class="flex flex-col justify-center w-96">
                <div class="px-4 py-2 font-bold text-white bg-green-500 rounded-t">Réussite</div>
                    <div class="px-4 py-3 text-green-700 bg-green-100 border border-t-0 border-green-400 rounded-b">
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-green-700"
                        >{{ __('Profil sauvegardé.') }}</p>
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
                        <option value="10" id="optionValue">selectionner</option>
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
            <form method="post" action="{{route('transactionsFilterEmail')}}" id="formulaireEmail">
                @csrf
                <input id="valueForm" type="email" placeholder="Entrez l'email du client" name="email" class="w-96" required>
                <button class="bouton">Rechercher</button>
            </form>
        </div>



        <div class="p-10 mb-12 m_auto"><!-- Bloc du tableau des transactions -->
            <table>
                <thead id="transactionTable">
                    <tr class=" bg-[#178CA4] text-white">
                    <th class="w-1/6 p-4 m-auto border-2 border-solid">ID_Opération</th>
                    <th class="w-1/6 m-auto border-2 border-solid">Opération</th>
                    <th class="w-1/6 m-auto border-2 border-solid">Nom du client</th>
                    <th class="w-1/6 m-auto border-2 border-solid">Montant</th>
                    <th class="w-1/6 m-auto border-2 border-solid">Date</th>
                    <th class="w-1/6 m-auto border-2 border-solid">Status</th>
                    </tr>
                </thead>
                <tbody id="detailsTransaction">

                    @foreach ($transactions as $transaction)
                        @if ($transaction->etat_transactions->label == "Terminé")
                            @php
                                $class_value = " bg-green-500 text-white";
                            @endphp
                        @elseif ($transaction->etat_transactions->label == "Annulé")
                            @php
                                $class_value = " bg-red-500 text-white";
                            @endphp
                        @else
                            @php
                            $class_value = "bg-white";
                            @endphp
                        @endif
                    <tr>

                    <td id="transactionId" class="p-5 m-auto text-center bg-white border-2 border-solid">{{$transaction->id}}</td>
                    <td id="transactionLabel"  class="m-auto text-center bg-white border-2 border-solid">{{$transaction->type_transactions->label}}</td>
                    <td id="transactionNom" class="m-auto text-center bg-white border-2 border-solid">
                        @if ($transaction->id_compte_envoyeur==null)
                            {{$transaction->comptes_bancaire_receveur->comptes->nom}}
                            {{$id_compte = $transaction->id_compte_receveur}}
                        @else
                            {{ $transaction->comptes_bancaire->comptes->nom}}
                            {{$id_compte = $transaction->id_compte_envoyeur}}
                        @endif


                    </td>
                    <td id="transactionEmail" class="m-auto text-center bg-white border-2 border-solid">

                            {{ $transaction->montant }} $


                    </td>
                    <td id="transactionDate" class="m-auto text-center bg-white border-2 border-solid">{{$transaction->created_at->format('d-M-Y')}}</td>
                    <td id="transactionEtat" class="m-auto text-center border-2 border-solid {{$class_value}}">{{$transaction->etat_transactions->label}}</td>

                            <td id="transactionVoir" class="m-auto text-center border-none">
                                <a href="{{ route('transactions', ['id_compte_envoyeur' => $id_compte]) }}" class="bouton">Voir</a>
                            </td>

                    </tr>
                    @endforeach

                </tbody>
                </table>
        </div><!-- Fin du bloc du tableau des transactions -->


</x-app-layout>
