<!DOCTYPE html>
<html lang="fr-CA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rapport</title>
</head>
<body>
    <h1>{{ $titre }}</h1>
    <h2>{{ $dates }}</h2>

    @if (!empty($transactions))
        <h3>{{ __('Transactions') }}</h3>

        <table>
            <thead>
                <tr>
                    <th>{{ __('ID de transaction') }}</th>
                    <th>{{ __('Montant') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Envoyeur') }}</th>
                    <th>{{ __('Receveur') }}</th>
                    <th>{{ __('État') }}</th>
                    <th>{{ __('Créé le') }}</th>
                    <th>{{ __('Mis à jour le') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->montant }}</td>
                        <td>{{ $transaction->type_transactions->label }}</td>
                        <td>{{ $transaction->comptes_bancaire->comptes->prenom }} {{ $transaction->comptes_bancaire->comptes->nom }}</td>
                        <td>{{ $transaction->comptes_bancaire_receveur->comptes->prenom }} {{ $transaction->comptes_bancaire_receveur->comptes->nom }}</td>
                        <td>{{ $transaction->etat_transactions->label }}</td>
                        <td>{{ $transaction->created_at }}</td>
                        <td>{{ $transaction->updated_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if (!empty($demandes))
        <h3>{{ __('Demandes') }}</h3>

        <table>
            <thead>
                <tr>
                    <th>{{ __('ID de demande') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Raison') }}</th>
                    <th>{{ __('Demandeur') }}</th>
                    <th>{{ __('État') }}</th>
                    <th>{{ __('Date demande') }}</th>
                    <th>{{ __('Date traitement') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($demandes as $demande)
                    <tr>
                        <td>{{ $demande->id }}</td>
                        <td>{{ $demande->type_demande->label }}</td>
                        <td>{{ $demande->raison }}</td>
                        <td>{{ $demande->user_demande->prenom }} {{ $demande->user_demande->nom }}</td>
                        <td>{{ $demande->date_demande }}</td>
                        <td>{{ $demande->date_traitement }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
