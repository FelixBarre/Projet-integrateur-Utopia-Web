<x-app-layout>




    <div class="flex flex-row"><!-- Premier bloc contenant message, infos et datetime -->

        <div class="w-2/4 text-left">
            <h2 class="text-3xl font-bold text-white mx-9">Liste des transactions de Fournisseurs partenaires</h2>
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
    <div class="flex justify-center w-full mb-10">
        <div role="alert" class="flex flex-col justify-center w-96">
            <div class="px-4 py-2 font-bold text-white bg-red-500 rounded-t">Erreur</div>
                <div class="px-4 py-3 text-red-700 bg-red-100 border border-t-0 border-red-400 rounded-b">
                    <p>{{ Session::get('error') }}</p>
                </div>
            </div>
        </div>

    </div>

    @endif



    </div><!-- Fin section filter -->

    <div class="p-10 mb-12 m_auto"><!-- Bloc du tableau des transactions -->
        <table>
            <thead>
                <tr class=" bg-[#178CA4] text-white">
                <th class="w-1/6 p-4 m-auto border-2 border-solid">ID_Fournisseur</th>
                <th class="w-1/6 m-auto border-2 border-solid">Nom</th>
                <th class="w-1/6 m-auto border-2 border-solid">Description</th>
                </tr>
            </thead>
            <tbody">
                @foreach ($fournisseurs as $fournisseur)
                <tr>
                    <td class="p-5 m-auto text-center bg-white border-2 border-solid">{{$fournisseur->id}}</td>
                    <td class="m-auto text-center bg-white border-2 border-solid">{{$fournisseur->nom}}</td>
                    <td class="m-auto text-center bg-white border-2 border-solid">{{$fournisseur->description}}</td>
                </tr>
                @endforeach

            </tbody>
            </table>
    </div><!-- Fin du bloc du tableau des transactions -->


</x-app-layout>
