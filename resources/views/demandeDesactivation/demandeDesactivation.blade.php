<x-app-layout>

    <div class="w-2/4 text-left mt-5">
        <h2 class="text-6xl font-bold text-white mx-9">Details de la demande</h2>
    </div>

    <div class="text-white">
        <h3 class="text-4xl text-center mt-20">{{$demande->user_demande->prenom}} {{$demande->user_demande->nom}}</h3>
        <h3 class="text-4xl text-center">{{$demande->user_demande->email}}</h3>
        <h3 class="text-4xl text-center">{{$demande->user_demande->telephone}}</h3>
    </div>

    <form action="{{ route('destroyCompte')}}" method="POST" id="formDesac">
        @csrf
        <div class="flex justify-evenly w-3/4 m-auto mt-16">
            <div class="flex flex-col items-center">
                <label class="text-3xl text-white mb-1" for="raison">Raison de la demande :</label>
                <input class="w-80" id="raisonDesac" name="raison" type="text" value="{{$demande->raison}}" readonly>
            </div>
            <div class="flex flex-col items-center">
                <label class="text-3xl text-white mb-1" for="montant">Date de la demande :</label>
                <input class="w-80" id="date_demandeDesac" name="montant" type="text" value="{{$demande->date_demande}}" readonly>
            </div>
        </div>

        <div class="w-3/4 m-auto flex justify-evenly mt-20">
            <input type="hidden" id="id_demandeDesac" name="id_demandeDesac" value="{{$demande->id}}">
            <button class="bouton w-40" id="btnApprouverDesac" name="actionDesac" value="approuver">Approuver</button>
            <button class="bouton w-40" id="btnRefuserDesac" name="actionDesac" value="refuser">Refuser</button>
            <button class="bouton w-40" name="actionDesac" value="retour">Retour</button>
        </div>
    </form>

</x-app-layout>
