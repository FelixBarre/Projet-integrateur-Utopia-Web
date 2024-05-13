<x-app-layout>

    <div class="flex flex-row"><!-- Premier bloc contenant message, infos et datetime -->

            <div class="w-2/4 text-left">
                <h2 class="text-3xl font-bold text-white mx-9">Liste des transactions de : <br> {{$compte->prenom}} {{$compte->nom}}</h2>
            </div>

            <div class="w-2/4 text-right text-white">

                <div class="flex flex-row items-center space-x-3 place-content-end">
                    <span class="text-2xl font-bold">Employé {{$employe->prenom}} {{ $employe->nom }}</span><span><img class="size-14" src="{{ asset('img/profile.png') }}" alt="profile-img"></span>
                </div>


                <div>
                    <p class="mr-20 text-white">{{$date_time }}</p>
                </div>


            </div>

    </div> <!-- Fin du premier bloc -->



    <div class="">


        <div class="flex flex-row justify-center">
            <form method="post" action="{{route('transactionsFilter')}}" id="formulaireEmail">
                @csrf
                <input id="valueForm" type="number" placeholder="Entrez le montant de l'opération" name="montant" class="w-96" required>
                <input type="hidden" value="{{$transaction->id}}" name="id_compte_user">
                <button class="bouton">Rechercher</button>
            </form>
        </div>


    </div><!-- Fin section filter -->

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
                                {{$class_value = " bg-green-500 text-white";}}
                            @endphp

                        @elseif ($transaction->etat_transactions->label == "Annulé")
                            @php
                                {{$class_value = " bg-red-500 text-white";}}
                            @endphp

                        @else
                            @php
                                {{$class_value = "bg-white";}}
                            @endphp

                        @endif
                <tr>
                <td id="transactionId" class="p-4 m-auto text-center bg-white border-2 border-solid">{{$transaction->id}}</td>
                <td id="transactionLabel" class="m-auto text-center bg-white border-2 border-solid">{{$transaction->type_transactions->label}}</td>
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
                    @if ($transaction->etat_transactions->label != "Terminer" && $transaction->etat_transactions->label != "Annuler")
                        <td class="m-auto text-center border-none">
                           <a class="bouton" href="{{
                            route('transaction', ['id_transaction' => $transaction->id]) }}"> Détails </a>

                        </td>

                    @endif

                </tr>
                @endforeach

            </tbody>
            </table>
    </div><!-- Fin du bloc du tableau des transactions -->


    <div class="flex items-end justify-end">
        <a href="{{ route('accueil') }}"><button class="bouton">Retour à l'accueil</button></a>
    </div>



</x-app-layout>
