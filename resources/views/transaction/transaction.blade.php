<x-app-layout>

    <div class="flex flex-row"><!-- Premier bloc contenant message, infos et datetime -->

            <div class="w-2/4 text-left ">
                <h2 class="text-3xl font-bold text-white mx-9">Détails de la transation<br>
                    Transaction N° : {{$transaction->id}}
                </h2>
            </div>

            <div class="w-2/4 text-right text-white">

                <div class="flex flex-row items-center space-x-3 place-content-end">
                    <span class="text-2xl font-bold">Employé {{$employe->prenom}} {{ $employe->nom }}</span><span><img class="size-14" src="{{ asset('img/profile.png') }}" alt="profile-img"></span>
                </div>


                <div>
                    <p class="mr-20 tex9t-white">{{$date_time }}</p>
                </div>


            </div>

    </div> <!-- Fin du premier bloc -->


    <div class="flex flex-row my-5 mx-9"><!-- Bloc détails -->




        <div class="w-2/4 "><!-- Bloc expéditeur -->

            <p class="mt-10 mb-5 text-2xl font-bold text-white underline">Expéditeur</p>

            @if ($transaction->id_compte_envoyeur!=null)
            <form action="#">
                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Nom :  </label>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire->comptes->nom}}" class="text-xl" disabled>
                </p>

                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Prenom :  </label>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire->comptes->prenom}}" class="text-xl" disabled>
                </p>

                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Type d'operation :  </label>
                    <input type="text" name="nom" value="{{$transaction->type_transactions->label}}" class="text-xl" disabled>
                </p>

                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Numero de compte :  </label>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire->id}}" class="text-xl" disabled>
                </p>

                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Type de compte :  </label>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire->nom}}" class="text-xl" disabled>
                </p>

                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Montant :  </label>
                    <input type="text" name="nom" value="{{$transaction->montant}}" class="text-xl" disabled>
                </p>


                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Etat de la transaction :  </label>
                    <input type="text" name="nom" value="{{$transaction->etat_transactions->label}}" class="text-xl" disabled>
                </p>



                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Date de la transaction :  </label>
                    <input type="text" name="nom" value="{{$transaction->created_at->format('d-M-Y')}}" class="text-xl" disabled>
                </p>


            </form>

            @else
                <p class="flex items-center mb-5 text-xl italic text-white">
                    Le compte expediteur n'a pas été trouvé. car l'utilisateur à effectué est dépôt.<br>
                </p>

            @endif


        </div>


        <div class="w-2/4"><!-- Bloc destinataire -->

            <p class="mt-10 mb-5 text-2xl font-bold text-white underline">Destinataire</p>

            @if($transaction->id_compte_receveur != null)

            <form action="#">
                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Nom :  </label>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire_receveur->comptes->nom}}" class="text-xl" disabled>
                </p>

                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Prenom :  </label>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire_receveur->comptes->prenom}}" class="text-xl" disabled>
                </p>


                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Numero de compte :  </label>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire_receveur->id}}" class="text-xl" disabled>
                </p>

                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Nom de compte :  </label>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire_receveur->nom}}" class="text-xl" disabled>
                </p>

                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Type de compte :  </label>
                    <input type="text" name="nom" value="{{$transaction->comptes_bancaire_receveur->nom}}" class="text-xl" disabled>
                </p>

                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Montant :  </label>
                    <input type="text" name="nom" value="{{$transaction->montant}}" class="text-xl" disabled>
                </p>


                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Etat de la transaction :  </label>
                    <input type="text" name="nom" value="{{$transaction->etat_transactions->label}}" class="text-xl" disabled>
                </p>



                <p class="flex items-center mb-5">
                    <label for="nom" class="w-64 text-xl text-white">Date de la transaction :  </label>
                    <input type="text" name="nom" value="{{$transaction->created_at->format('d-M-Y')}}" class="text-xl" disabled>
                </p>



            </form>



            @else

                <p class="flex items-center mb-5 text-xl italic text-white">
                    Le compte destinataire n'a pas été trouvé car l'utilisateur à une opération de liée à un(e) {{$transaction->type_transactions->label}}.<br>
                </p>

            @endif


        </div>





    </div>


    <div class="flex justify-start">
        <a href="{{ route('accueil') }}"><button class="bouton">Retour à l'accueil</button></a>
    </div>



</x-app-layout>
