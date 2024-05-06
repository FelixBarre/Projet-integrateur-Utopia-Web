<x-app-layout>

    <div class="flex flex-row"><!-- Premier bloc contenant message, infos et datetime -->

            <div class="w-2/4 text-left">
                <h2 class="text-3xl font-bold text-white mx-9">Liste des transactions de : <br>{{$transaction->comptes_bancaire->comptes->prenom}} {{$transaction->comptes_bancaire->comptes->nom}}</h2>
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

    <div role="alert" class="flex items-center justify-center hidden" id="error-class">

        <div class="p-2 text-red-700 bg-red-100 border border-t-0">
        <div class="font-bold text-white bg-red-500">Erreur</div>
        <p>Veuillez choisir la date de début</p>
        </div>
    </div>

    <div class="flex flex-row"><!--Section filter -->
        <form method="post" action="{{ route('transactionsFilterUser')}}" id="formSelect" class="w-2/4">
            @csrf
            <p class="my-5 mx-9">
                <label for="filter" class="text-lg text-white">Trier par </label>

                <select id="selectValue" name="id_type_Transaction" class="w-64 p-3">
                    <option value="none" id="optionValue">Sélectionner</option>
                    @foreach ($type_transactions as $type_transaction)
                    <option value="{{$type_transaction->id}}" id="optionValue">{{$type_transaction->label}}</option>
                    @endforeach
                </select>

                <input type="hidden" name="id_compte_envoyeur" value="{{ $id_compte_envoyeur }}">
            </p>
        </form>

        <!--<form method="post" action="{{ route('transactionsFilterDateUser')}}" class="w-2/4 mr-20 text-right" id="formDate">
            @csrf
            <p class="items-center my-5 mx-9" >
                <label for="filter" class="text-lg text-white">Filtrer de </label>
                <input type="date" name="date_debut">
                <label for="filter" class="text-lg text-white">à</label>
                <input type="date" name="date_fin" id="date_fin">

            </p>


        </form>-->



    </div><!-- Fin section filter -->

    <div class="p-10 mb-12 m_auto"><!-- Bloc du tableau des transactions -->
        <table>
            <thead>
                <tr class=" bg-[#178CA4] text-white">
                <th class="w-1/6 p-4 m-auto border-2 border-solid">ID_Opération</th>
                <th class="w-1/6 m-auto border-2 border-solid">Opération</th>
                <th class="w-1/6 m-auto border-2 border-solid">Nom du client</th>
                <th class="w-1/6 m-auto border-2 border-solid">E-mail</th>
                <th class="w-1/6 m-auto border-2 border-solid">Date</th>
                <th class="w-1/6 m-auto border-2 border-solid">Status</th>
                </tr>
            </thead>
            <tbody">
                @php

                @endphp
                @foreach ($transactions as $transaction)
                    @if ($transaction->etat_transactions->label != "En cours")
                        @php
                            $class_value = " bg-green-500 ";
                        @endphp
                    @else
                        @php
                        $class_value = "bg-white";
                        @endphp
                    @endif
                <tr>
                <td class="p-4 m-auto text-center bg-white border-2 border-solid">{{$transaction->id}}</td>
                <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->type_transactions->label}}</td>
                <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->comptes_bancaire->comptes->nom}}</td>
                <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->comptes_bancaire->comptes->email}}</td>
                <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->created_at->format('d-M-Y')}}</td>
                <td class="m-auto text-center border-2 border-solid {{$class_value}}">{{$transaction->etat_transactions->label}}</td>
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


    <div class="flex justify-start">
        <a href="{{ route('accueil') }}"><button class="bouton">Retour à l'accueil</button></a>
    </div>



</x-app-layout>
