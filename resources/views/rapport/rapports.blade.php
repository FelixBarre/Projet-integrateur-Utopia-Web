<x-app-layout>
    <div class="p-10">
        <h2 class="font-semibold text-3xl leading-tight text-white">
        {{ __('Liste des rapports') }}
        </h2>

        <a href="{{ route('nouveauRapport') }}" class="text-2xl inline-block my-5 bouton">{{ __('+ Nouveau rapport') }}</a>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <table class="w-full text-center bg-white border-solid border-2">
                <thead class="bg-[#178CA4] text-white">
                    <tr>
                        <th class="border-solid border-2">Titre</th>
                        <th class="border-solid border-2">Description</th>
                        <th class="border-solid border-2">Début</th>
                        <th class="border-solid border-2">Fin</th>
                        <th class="border-solid border-2">Créé le</th>
                        <th class="border-solid border-2">Créé par</th>
                        <th class="border-solid border-2">Consulter</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rapports as $rapport)
                        <tr class="h-10">
                            <td class="border-solid border-2">{{ $rapport->titre }}</td>
                            <td class="border-solid border-2">{{ $rapport->description }}</td>
                            <td class="border-solid border-2">{{ $rapport->date_debut }}</td>
                            <td class="border-solid border-2">{{ $rapport->date_fin }}</td>
                            <td class="border-solid border-2">{{ $rapport->date_creation }}</td>
                            <td class="border-solid border-2">{{ $rapport->employe->prenom }} {{ $rapport->employe->nom }}</td>
                            <td class="border-solid border-2"><a class="bouton" href="{{ url($rapport->chemin_du_fichier) }}">Voir</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
