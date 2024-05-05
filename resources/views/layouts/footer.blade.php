<footer class="flex flex-row mt-12 text-white divide-x ml-36"><!-- Debut du footer -->

    <div class="w-1/4 p-3">
        <p class="mb-3 text-2xl font-bold"><span><i class="fas fa-wallet"></i> </span> <span>Gestion de comptes</span> </p>
        <p><a href="{{ route('register')}}">Création des comptes utilisateurs</a></p>
        <p><a href="{{ route('profile.edit')}}">Modification de profil</a></p>
        <p><a href="{{ route('password.change')}}">Changement de mot de passe </a></p>
        <p><a href="">Consultation des demandes de désactivation de compte </a></p>
        <p><a href="">Modification des demandes de désactivation de compte </a></p>
    </div>

    <div class="w-1/4 p-3">
        <p class="mb-3 text-2xl font-bold"><span></span><i class="fas fa-address-card"></i> <span> Gestion de profils</span></p>
        <p><a href="">Modification des informations personnelles</a></p>
        <p><a href="">Gestion des préférences </a></p>

    </div>

    <div class="w-1/4 p-3">
        <p class="mb-3 text-2xl font-bold"><span><i class="fas fa-retweet"></i> </span><span>Gestion des opérations</span></p>

        <p><a href="">Virement</a></p>
        <p><a href="{{ route('accueil')}}">Transactions</a></p>
        <p><a href="">Facture</a></p>
        <p><a href="{{ route('demandesPret')}}">Consulter les demandes de prêt </a></p>

    </div>

    <div class="w-1/4 p-3">
        <p class="mb-3 text-2xl font-bold"><span><i class="fas fa-clipboard"></i></span> <span>Gestion de rapports</span> </p>
        <p><a href="">Création des rapports </a></p>
        <p><a href="">Consultation des rapports </a></p>

    </div>

</footer><!-- Fin du footer -->

