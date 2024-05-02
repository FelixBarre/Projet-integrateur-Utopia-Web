<x-app-layout>

    <div class="flex flex-row"><!-- Premier bloc contenant message, infos et datetime -->

            <div class="w-2/4 text-left ">
                <h2 class="text-3xl font-bold text-white mx-9">Détails de la transation <br>Client :  {{$transaction->comptes_bancaire->comptes->prenom}} {{$transaction->comptes_bancaire->comptes->nom}} <br>
                    Transaction N° : {{$transaction->id}}
                </h2>
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


    <div class="flex flex-row my-5 mx-9"><!-- Bloc détails -->

        <div class="w-1/4 "><!-- Bloc expéditeur -->
        <p class="mt-10 mb-5 text-2xl font-bold text-white underline">Expéditeur</p>
            <form action="#">
                <p class="mb-5">
                    <label for="nom" class="text-xl text-white w-80">Nom :  </label><br>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire->comptes->nom}}" class="text-xl" disabled>
                </p>

                <p class="mb-5">
                    <label for="nom" class="w-32 text-xl text-white">Prenom :  </label><br>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire->comptes->prenom}}" class="text-xl" disabled>
                </p>

                <p class="mb-5">
                    <label for="nom" class="w-32 text-xl text-white">Type d'operation :  </label><br>
                    <input type="text" name="nom" value="{{$transaction->type_transactions->label}}" class="text-xl" disabled>
                </p>

                <p class="mb-5">
                    <label for="nom" class="w-32 text-xl text-white">Numero de compte :  </label><br>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire->id}}" class="text-xl" disabled>
                </p>

                <p class="mb-5">
                    <label for="nom" class="w-32 text-xl text-white">Type de compte :  </label><br>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire->nom}}" class="text-xl" disabled>
                </p>

                <p class="mb-5">
                    <label for="nom" class="w-32 text-xl text-white">Montant :  </label><br>
                    <input type="text" name="nom" value="{{$transaction->montant}}" class="text-xl" disabled>
                </p>


                <p class="mb-5">
                    <label for="nom" class="w-32 text-xl text-white">Etat de la transaction :  </label><br>
                    <input type="text" name="nom" value="{{$transaction->etat_transactions->label}}" class="text-xl" disabled>
                </p>



                <p class="mb-5">
                    <label for="nom" class="w-32 text-xl text-white">Date de la transaction :  </label><br>
                    <input type="text" name="nom" value="{{$transaction->created_at}}" class="text-xl" disabled>
                </p>


            </form>

        </div>



        <div class="w-1/4"><!-- Bloc destinataire -->

            <p class="mt-10 mb-5 text-2xl font-bold text-white underline">Destinataire</p>

            <form action="#">
                <p class="mb-5">
                    <label for="nom" class="text-xl text-white w-80">Nom :  </label><br>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire_receveur->comptes->nom}}" class="text-xl" disabled>
                </p>

                <p class="mb-5">
                    <label for="nom" class="w-32 text-xl text-white">Prenom :  </label><br>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire_receveur->comptes->prenom}}" class="text-xl" disabled>
                </p>


                <p class="mb-5">
                    <label for="nom" class="w-32 text-xl text-white">Numero de compte :  </label><br>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire_receveur->id}}" class="text-xl" disabled>
                </p>

                <p class="mb-5">
                    <label for="nom" class="w-32 text-xl text-white">Nom de compte :  </label><br>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire_receveur->nom}}" class="text-xl" disabled>
                </p>


            </form>

        </div>

    </div>





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
