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
                        <p class="mr-20 tex9t-white">{{$date_time }}</p>
                    </div>


                </div>

        </div> <!-- Fin du premier bloc -->

        <div><!--Section filter -->
            <form method="post" action="{{ route('transactionsFilter')}}" id="formSelect">
                @csrf
                <p class="my-5 mx-9">
                    <label for="filter" class="text-lg text-white">Filtrer par </label>

                    <select id="selectValue" name="id_type_Transaction" class="w-64 p-3">
                        <option value="none" id="optionValue">Sélectionner</option>
                        @foreach ($type_transactions as $type_transaction)
                        <option value="{{$type_transaction->id}}" id="optionValue">{{$type_transaction->label}}</option>
                        @endforeach
                    </select>

                </p>


            </form>

        </div><!-- Fin section filter -->



        <div class="p-10 m_auto"><!-- Bloc du tableau des transactions -->
            <table>
                <thead>
                    <tr class=" bg-[#178CA4] text-white">
                    <th class="w-1/6 p-4 m-auto border-2 border-solid">IdDemande</th>
                    <th class="w-1/6 m-auto border-2 border-solid">Tâche</th>
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
                        @if ($transaction->etat_transactions->label == "Terminé")
                            @php
                                $class_value = " bg-green-500 ";
                            @endphp
                        @elseif ($transaction->etat_transactions->label == "Annulé")
                            @php
                                $class_value = " bg-red-500 ";
                            @endphp
                        @else
                            @php
                            $class_value = "bg-white";
                            @endphp
                        @endif
                    <tr>
                    <td class="p-5 m-auto text-center bg-white border-2 border-solid">{{$transaction->id}}</td>
                    <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->type_transactions->label}}</td>
                    <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->comptes_bancaire->comptes->nom}}</td>
                    <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->comptes_bancaire->comptes->email}}</td>
                    <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->created_at->format('d-M-Y')}}</td>
                    <td class="m-auto text-center border-2 border-solid {{$class_value}}">{{$transaction->etat_transactions->label}}</td>

                            <td class="m-auto text-center border-none">
                               <a  href="{{
                                route('transactions', ['id_compte_envoyeur' => $transaction->id_compte_envoyeur]) }}" class="bouton"> Voir </a>

                    </tr>
                    @endforeach

                </tbody>
                </table>
        </div><!-- Fin du bloc du tableau des transactions -->

        <footer class="flex flex-row text-white mx-9"><!-- Debut du footer -->

            <div class="w-1/4">
                <p class="mb-3 font-bold">Gestion de comptes</p>
                <p><a href="{{ route('register')}}">Création des comptes utilisateurs</a></p>
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
