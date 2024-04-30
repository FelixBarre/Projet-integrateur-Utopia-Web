<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bienvenue sur Utopia</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>

    </head>


    <body class="flex flex-row h-screen">

            <nav class="bg-[#F9F7F0] flex flex-col w-24 p-1 h-2/5"><!--Section de gauche -->
                <a class="p-3" href=""><img src="{{asset('img/letter-u.png')}}" alt=""></a>
                <a class="p-2 m-auto my-3 w-14" href=""><img src="{{asset('img/receive-mail.png')}}" alt=""></a>
                <a class="p-2 m-auto my-3 w-14" href=""><img src="{{asset('img/money-exchange.png')}}" alt=""></a>
                <a class="p-2 m-auto my-3 w-14" href=""><img src="{{asset('img/info.png')}}" alt=""></a>
                <a class="p-2 m-auto my-3 w-14" href=""><img src="{{asset('img/chat.png')}}" alt=""></a>
                <a class="p-2 m-auto my-3 w-14" href=""><img src="{{asset('img/user.png')}}" alt=""></a>
            </nav>

            <div class="bg-[#18B7BE] w-full"><!--Section de droite -->

                <header class="grid items-center grid-cols-2 gap-2 py-10 lg:grid-cols-3">

                    @if (Route::has('login'))
                        <nav>
                            @auth
                            <span>
                                <a
                                    href="{{ url('/dashboard') }}"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                >
                                    Employé {{Auth::user}}
                                </a>

                            </span>

                            <span>
                                <img src="{{ asset('img/') }}" alt="icon user">
                            </span>


                            @endauth
                        </nav>
                    @endif
                </header>

                <div class="flex flex-row w-full">

                    <div class="w-2/4 text-left">
                        <h2 class="text-6xl font-bold text-white mx-9">Bonjour !</h2>
                    </div>

                    <div class="w-2/4 text-right text-white">

                        <div class="flex flex-row items-center space-x-3 place-content-end">
                            <span class="text-2xl font-bold">Employé 000</span><span><img class="size-14" src="{{ asset('img/profile.png') }}" alt="profile-img"></span>
                        </div>


                        <div>
                            @php
                                use Carbon\Carbon;
                            @endphp
                            <p class="mr-20 tex9t-white">{{Carbon::now()->format('d-M-Y H:i') }}</p>

                        </div>


                    </div>

                </div>

                <div><!--Section filter -->
                    <form action="">
                        <p class="my-5 mx-9">
                            <label for="filter" class="text-lg text-white">Filtrer par </label>
                            <select name="typeTransaction" id="typeTransaction" class="w-64 p-3">
                                @foreach ($type_transactions as $type_transaction)
                                <option value="{{$type_transaction->label}}">{{$type_transaction->label}}</option>
                                @endforeach

                            </select>
                        </p>

                    </form>

                </div>




                <main class="p-10 m_auto">
                    <table>
                        <thead>
                            <tr class=" bg-[#178CA4] text-white">
                            <th class="w-1/6 m-auto border-2 border-solid ">IdDemande</th>
                            <th class="w-1/6 m-auto border-2 border-solid">Tâche</th>
                            <th class="w-1/6 m-auto border-2 border-solid">Nom du client</th>
                            <th class="w-1/6 m-auto border-2 border-solid">Téléphone</th>
                            <th class="w-1/6 m-auto border-2 border-solid">Date</th>
                            <th class="w-1/6 m-auto border-2 border-solid">Status</th>
                            </tr>
                        </thead>
                        <tbody">
                            <tr>
                            @foreach ($transactions as $transaction)
                            <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->id}}</td>
                            <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->type_transactions->label}}</td>
                            <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->comptes->nom}}</td>
                            <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->comptes->email}}</td>
                            <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->comptes->created_at}}</td>
                            <td class="m-auto text-center bg-white border-2 border-solid">{{$transaction->etat_transactions->label}}</td>
                                @if ($transaction->etat_transactions->label != "Terminer")
                                    <td class="m-auto text-center border-none">
                                        <a class="p-1 mt-3 ml-4 text-white bg-blue-600 rounded hover:bg-blue-70"
                                            href="{{ route('transactionUpdate') }}"

                                        >
                                            Modifier
                                        </a>
                                    </td>



                                @endif

                            @endforeach

                            </tr>

                        </tbody>
                        </table>
                </main>

                <footer class="flex flex-row text-white mx-9">

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

                </footer>




        </div>



    </body>
</html>
