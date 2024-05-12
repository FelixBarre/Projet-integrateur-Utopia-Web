<x-app-layout>




    <div class="flex flex-row"><!-- Premier bloc contenant message, infos et datetime -->

        <div class="w-2/4 text-left">
            <h2 class="text-3xl font-bold text-white mx-9">Liste des Fournisseurs partenaires</h2>
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



    <div class="flex flex-row justify-center">
        <form method="post" action="{{route('fournisseurFilter')}}" id="formulaireEmail">
            @csrf
            <input id="valueForm" type="text" placeholder="Entrez le nom du fournisseur" name="fournisseur" class="w-96" required>
            <button class="bouton">Rechercher</button>
        </form>
    </div>

    <div class="p-10 m-auto mb-12"><!-- Bloc du tableau des transactions -->
        <table>
            <thead>
                <tr class=" bg-[#178CA4] text-white w-full">
                    <th class="p-4 m-auto border-2 border-solid w-96">ID_Fournisseur</th>
                    <th class="m-auto border-2 border-solid w-96">Nom</th>
                    <th class="m-auto border-2 border-solid w-96">Description</th>
                </tr>
            </thead>
            <tbody">
                @foreach ($fournisseurs as $fournisseur)
                <tr>
                    <td class="p-5 m-auto text-center bg-white border-2 border-solid ">{{$fournisseur->id}}</td>
                    <td class="m-auto text-center bg-white border-2 border-solid ">{{$fournisseur->nom}}</td>
                    <td class="m-auto text-center bg-white border-2 border-solid ">{{$fournisseur->description}}</td>
                </tr>
                @endforeach

            </tbody>
            </table>
    </div><!-- Fin du bloc du tableau des transactions -->


</x-app-layout>
