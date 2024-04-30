<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Liste des rapports') }}
    </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <table class="w-full text-center">
                        <thead class="bg-gradient-to-b from-cyan-500 to-blue-500">
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Créé le</th>
                            <th>Créé par</th>
                            <th>Consulter</th>
                        </thead>
                        <tbody>
                            @foreach ($rapports as $rapport)
                                <tr class="h-10">
                                    <td>{{ $rapport->titre }}</td>
                                    <td>{{ $rapport->description }}</td>
                                    <td>{{ $rapport->date_debut }}</td>
                                    <td>{{ $rapport->date_fin }}</td>
                                    <td>{{ $rapport->date_creation }}</td>
                                    <td>{{ $rapport->employe->prenom }} {{ $rapport->employe->nom }}</td>
                                    <td><a class="bouton" href="{{ $rapport->chemin_du_fichier }}">Voir</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
