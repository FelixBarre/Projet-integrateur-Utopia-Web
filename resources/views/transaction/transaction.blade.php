<x-app-layout>

    <div class="flex flex-row"><!-- Premier bloc contenant message, infos et datetime -->

            <div class="w-2/4 text-left">
                <h2 class="text-3xl font-bold text-white mx-9">Liste des transactions de : <br>{{$user->prenom}} {{$user->nom}}</h2>
            </div>

            <div class="w-2/4 text-right text-white">

                <div class="flex flex-row items-center space-x-3 place-content-end">
                    <span class="text-2xl font-bold">Employé {{$employe->name}}</span><span><img class="size-14" src="{{ asset('img/profile.png') }}" alt="profile-img"></span>
                </div>


                <div>
                    <p class="mr-20 tex9t-white">{{$date_time }}</p>
                </div>


            </div>

    </div> <!-- Fin du premier bloc -->

    <div><!--Section filter -->
        <form action="">
            <p class="my-5 mx-9">
                <label for="filter" class="text-lg text-white">Filtrer par </label>
                <select name="typeTransaction" id="typeTransaction" class="w-64 p-3">
                    @foreach ($type_transactions as $type_transaction)
                    <option value="{{$type_transaction->label}}">{{$type_transaction->label}}</option>
                    @endforeach

                </select>
            </p>

        </form>

    </div><!-- Fin section filter -->



    <div class="p-10 m_auto"><!-- Bloc du tableau des transactions -->
        <table>
            <thead>
                <tr class=" bg-[#178CA4] text-white">
                <th class="w-1/6 m-auto border-2 border-solid ">IdDemande</th>
                <th class="w-1/6 m-auto border-2 border-solid">Tâche</th>
                <th class="w-1/6 m-auto border-2 border-solid">Nom du client</th>
                <th class="w-1/6 m-auto border-2 border-solid">Téléphone</th>
                <th class="w-1/6 m-auto border-2 border-solid">Date</th>
                <th class="w-1/6 m-auto border-2 border-solid">Status</th>
                </tr>
            </thead>
            <tbody>
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
                <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->id}}</td>
                <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->type_transactions->label}}</td>
                <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->comptes->nom}}</td>
                <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->comptes->email}}</td>
                <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->comptes->created_at}}</td>
                <td class="m-auto text-center border-2 border-solid {{$class_value}}">{{$transaction->etat_transactions->label}}</td>
                    @if ($transaction->etat_transactions->label != "Terminer" && $transaction->etat_transactions->label != "Annuler")
                        <td class="m-auto text-center border-none">
                           <a href="{{
                            route('transactionView', ['id_compte_envoyeur' => $transaction->id]) }}""> Voir </a>



                    @endif

                </tr>
                @endforeach

            </tbody>
            </table>
    </div><!-- Fin du bloc du tableau des transactions -->

    <footer class="flex flex-row text-white mx-9"><!-- Debut du footer -->

        <div class="w-1/4">
            <p class="mb-3 font-bold">Gestion de comptes</p>
            <p><a href="">Création des comptes utilisateurs</a></p>
            <p><a href="">Modification de profil</a></p>
            <p><a href="">Changement de mot de passe </a></p>
            <p><a href="">Consultation des demandes de désactivation de compte </a></p>
            <p><a href="">Modification des demandes de désactivation de compte </a></p>
        </div>

        <div class="w-1/4">
            <p class="mb-3 font-bold">Gestion de profils</p>
            <p><a href="">Modification des informations personnelles</a></p>
            <p><a href="">Gestion des préférences </a></p>

        </div>

        <div class="w-1/4">
            <p class="mb-3 font-bold">Gestion des opérations</p>
            <p><a href="">Virement</a></p>
            <p><a href="">Transaction</a></p>
            <p><a href="">Facture</a></p>
            <p><a href="">Consulter une demande de prêt </a></p>
            <p><a href="">Modifier une demande de prêt </a></p>

        </div>

        <div class="w-1/4">
            <p class="mb-3 font-bold">Gestion de rapports</p>
            <p><a href="">Création des rapports </a></p>
            <p><a href="">Consultation des rapports </a></p>

        </div>

    </footer><!-- Fin du footer -->


</x-app-layout>
