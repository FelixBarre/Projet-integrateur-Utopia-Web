<x-app-layout>

    <div class="w-2/4 text-left mt-5">
        <h2 class="text-6xl font-bold text-white mx-9">Details de la demande</h2>
    </div>

    <div class="text-white">
        <h3 class="text-4xl text-center mt-20">{{$demande->user_demande->prenom}} {{$demande->user_demande->nom}}</h3>
        <h3 class="text-4xl text-center">{{$demande->user_demande->email}}</h3>
        <h3 class="text-4xl text-center">{{$demande->user_demande->telephone}}</h3>
    </div>

    <form action="{{ route('demandesPret')}}" method="GET" id="formPret">
        @csrf
        <div class="flex justify-evenly w-3/4 m-auto mt-16">
            <div class="flex flex-col items-center">
                <label class="text-3xl text-white mb-1" for="raison">Raison de la demande :</label>
                <input class="w-80" id="raison" name="raison" type="text" value="{{$demande->raison}}" readonly>
            </div>
            <div class="flex flex-col items-center">
                <label class="text-3xl text-white mb-1" for="montant">Montant demandé :</label>
                <input class="w-80" id="montant" name="montant" type="text" value="{{$demande->montant}}" readonly>
            </div>
        </div>

        <div class="flex justify-evenly w-3/4 m-auto mt-16">
            <div class="flex flex-col items-center items-center">
                <label class="text-3xl text-white mb-1" for="taux">Taux d'intérêt :</label>
                <div>
                    <input class="w-52" id="taux" name="taux" type="text">
                    <p class="inline text-white text-3xl ml-2">%</p>
                </div>
            </div>
            <div class="flex flex-col items-center">
                <label class="text-3xl text-white mb-1" for="duree">Durée :</label>
                <input class="w-60" id="duree" name="duree" type="text">
            </div>
        </div>

        <div class="w-3/4 m-auto flex justify-evenly mt-20">
            <input type="hidden" id="id_demande" value="{{$demande->id}}">
            <button class="bouton w-40" id="btnApprouver" name="actionPret" value="approuver">Approuver</button>
            <button class="bouton w-40" id="btnRefuser" name="actionPret" value="refuser">Refuser</button>
            <button class="bouton w-40" name="actionPret" value="retour">Retour</button>
        </div>

        <div class="text-center mt-5">
            <input class="w-40 rounded-3xl" name="motif" type="text" placeholder="Motif du refus">
        </div>

    </form>

</x-app-layout>
