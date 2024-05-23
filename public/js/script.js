var date_derniere_update = new Date().toLocaleString('sv-SE');

window.onload = function() {
    authenticateUser();
    pageAccueil();
    pageConversation();
    pageShowProfils();
    pageConversations();
    pagePret();
}

async function authenticateUser() {
    if (getCookie('TokenAPI') == '') {
        location.href = '/logout';
    } else {
        return;
    }
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

async function pageAccueil() {

    function formatDate(dateString) {
        const date = new Date(dateString);
        const options = { day: '2-digit', month: 'short', year: 'numeric' };
        return date.toLocaleDateString('fr-CA', options);
    }

    let formSelect = document.getElementById('formSelect');

    if(!formSelect) {
        return;
    }

    let selectValue = document.getElementById('selectValue');

    selectValue.addEventListener('change', async function(e){

        const selectedValue = this.value;

        if(selectedValue.value==10){
            e.preventDefault();
        }

        try{

            let response = await fetch("/api/transactions/filter/" + selectedValue , {
                method: 'GET',
                headers: {
                    'Accept': 'application/json; charset=utf-8',
                    'Content-Type': 'application/json; charset=utf-8',
                    'Authorization': `Bearer ${getCookie('TokenAPI')}`
                }
            });

            if(!response.ok){
                throw new Error('Une erreur est survenue.');
            }

            let transactions = await response.json();

            let detailsTransaction = document.getElementById('detailsTransaction');
            if(detailsTransaction){
               while(detailsTransaction.firstChild){
                    detailsTransaction.removeChild(detailsTransaction.lastChild);
               }
            }

            transactions.forEach(transaction => {

                let classEtatTransaction;

                if(transaction.id_etat_transaction=="Terminé"){
                    classEtatTransaction = "bg-green-500";
                }else if(transaction.id_etat_transaction=="Annulé"){
                    classEtatTransaction = "bg-red-500";
                }else{
                    classEtatTransaction= "bg-white";
                }

            let tbody = document.getElementById("detailsTransaction");

            let tr = document.createElement("tr")
            tr.classList.add("w-full");

            let tdID = document.createElement("td");
            tdID.classList.add("p-5", "m-auto", "text-center", "bg-white", "border-2", "border-solid");
            tdID.textContent = transaction.id;

            let tdOperation = document.createElement("td");
            tdOperation.classList.add("p-5", "m-auto", "text-center", "bg-white", "border-2", "border-solid");
            tdOperation.textContent = transaction.id_type_transaction;

            let idCompte;


            let tdNom = document.createElement("td");
            tdNom.classList.add("p-5", "m-auto", "text-center", "bg-white", "border-2", "border-solid");
            if(transaction.id_compte_envoyeur==null){
                idCompte = transaction.id_compte_receveur.id;
                tdNom.textContent = transaction.id_compte_receveur.nom;

            }else{
                idCompte = transaction.id_compte_envoyeur.id;
                tdNom.textContent = transaction.id_compte_envoyeur.nom;
            }


            let tdMontant = document.createElement("td");
            tdMontant.classList.add("p-5", "m-auto", "text-center", "bg-white", "border-2", "border-solid");
            tdMontant.textContent = transaction.montant +" $";


            let tdDate = document.createElement("td");
            tdDate.classList.add("p-5", "m-auto", "text-center", "bg-white", "border-2", "border-solid");
            tdDate.textContent = formatDate(transaction.created_at);

            let tdStatus = document.createElement("td");
            tdStatus.classList.add("border-2", "border-solid", "p-5", "m-auto", "text-center", classEtatTransaction);
            tdStatus.textContent = transaction.id_etat_transaction;

            let tdButton = document.createElement("td");
            tdButton.classList.add("m-auto", "text-center", "border-none");

            let a = document.createElement("a");
            a.classList.add("bouton");
            a.textContent = "Voir";
            a.setAttribute("href", "transactions/" +idCompte);

                tr.appendChild(tdID);
                tr.appendChild(tdOperation);
                tr.appendChild(tdNom);
                tr.appendChild(tdMontant);
                tr.appendChild(tdDate);
                tr.appendChild(tdStatus);
                tdButton.appendChild(a);
                tr.appendChild(tdButton);
                tbody.appendChild(tr);
            });
        } catch(error){
            console.log(error);
            alert('une erreur est survenue lors de la requête.');
        }
    });

    //Section recherche par email


    //section pour la recherche par date
    let formDate = document.getElementById("formDate");
    let dateDebut = document.getElementById("date_debut");
    let dateFin = document.getElementById("date_fin");

    dateFin.addEventListener('change', function(){
        formDate.submit();
    });
}

function pageConversation() {
    let boutonActionMessage = document.getElementById('boutonActionMessage');

    if (!boutonActionMessage) {
        return;
    }

    boutonActionMessage.addEventListener("click", actionMessage);

    let divConversation = document.getElementById('divConversation');
    divConversation.scrollTop = divConversation.scrollHeight;

    let texte = document.getElementById('texte');
    texte.addEventListener("keypress", function(e) {
        if(e.key == 'Enter') {
            e.preventDefault();
            actionMessage();
        }
    });

    let boutonsModifierMessage = document.querySelectorAll('.boutonModifierMessage');

    boutonsModifierMessage.forEach((bouton) => {
        bouton.addEventListener('click', modifierMessage);
    });

    let boutonsSupprimerMessage = document.querySelectorAll('.boutonSupprimerMessage');

    boutonsSupprimerMessage.forEach((bouton) => {
        bouton.addEventListener('click', supprimerMessage);
    });

    getNewMessages();

    getUpdatedMessages();
}

function pagePret() {
    formPret = document.getElementById('formPret');

    if (!formPret) {
        return;
    }

    btnApprouver = document.getElementById('btnApprouver');
    btnRefuser = document.getElementById('btnRefuser');

    btnApprouver.addEventListener('click', function (e) {
        let confirmation = confirm("Êtes-vous sûr de vouloir continuer ?\nLa demande ne pourra plus être modifiée.");

        if (confirmation)
            approuverPret(e);
        else
            return;
    });

    btnRefuser.addEventListener('click', function (e) {
        let confirmation = confirm("Êtes-vous sûr de vouloir continuer ?\nLa demande ne pourra plus être modifiée.");

        if (confirmation)
            refuserPret(e);
        else
            return;
    });
}

function pageShowProfils() {
    let boutonFiltre = document.getElementById('boutonFiltreProfiles');

    if (!boutonFiltre) {
        return;
    }

    boutonFiltre.addEventListener("click", filtrerProfils);
}

function pageConversations() {
    let boutonsSupprimerConversation = document.querySelectorAll('.boutonSupprimerConversation');

    if (!boutonsSupprimerConversation) {
        return;
    }

    boutonsSupprimerConversation.forEach((bouton) => {
        bouton.addEventListener('click', supprimerConversation);
    });
}

function pagePret() {
    formPret = document.getElementById('formPret');

    if (!formPret) {
        return;
    }

    btnApprouver = document.getElementById('btnApprouver');
    btnRefuser = document.getElementById('btnRefuser');

    btnApprouver.addEventListener('click', function (e) {
        let confirmation = confirm("Êtes-vous sûr de vouloir continuer ?\nLa demande ne pourra plus être modifiée.");

        if (confirmation)
            approuverPret(e);
        else
            return;
    });

    btnRefuser.addEventListener('click', function (e) {
        let confirmation = confirm("Êtes-vous sûr de vouloir continuer ?\nLa demande ne pourra plus être modifiée.");

        if (confirmation)
            refuserPret(e);
        else
            return;
    });
}

function envoyerMessage() {
    let boutonActionMessage = document.getElementById('boutonActionMessage');
    let id_message = document.getElementById('id_message');
    let pieceJointe = document.getElementById('pieceJointe');
    let texte = document.getElementById('texte');
    let action = document.getElementById('action');

    boutonActionMessage.innerHTML = 'Envoyer';
    id_message.value = '';
    texte.value = '';
    pieceJointe.value = '';
    texte.focus();
    action.value = 'POST';
}

function modifierMessage(event) {
    let boutonModifierMessage = event.currentTarget;
    let divRow = boutonModifierMessage.parentElement.parentElement;
    let pMessage = divRow.lastElementChild.lastElementChild.firstElementChild;
    let boutonActionMessage = document.getElementById('boutonActionMessage');
    let texte = document.getElementById('texte');
    let action = document.getElementById('action');
    let id_message = document.getElementById('id_message');

    boutonActionMessage.innerHTML = 'Modifier';

    texte.value = pMessage.innerHTML;
    texte.focus();

    action.value = 'PUT';

    id_message.value = divRow.id;
}

function supprimerMessage(event) {
    if (confirm('Êtes-vous certains de vouloir supprimer ce message?')) {
        let boutonSupprimerMessage = event.currentTarget;
        let divRow = boutonSupprimerMessage.parentElement.parentElement;
        let action = document.getElementById('action');
        let id_message = document.getElementById('id_message');

        action.value = 'DELETE';

        id_message.value = divRow.id;

        actionMessage();
    }
}

function alertErreurs(data) {
    if (!data['ERREUR']) {
        return;
    }

    let erreurs = data['ERREUR'];
    let messageErreurs = ''

    for (let erreur in erreurs) {
        messageErreurs += erreurs[erreur] + '\n';
    }

    alert(messageErreurs);
}

async function getNewMessages() {
    let divConversation = document.getElementById('divConversation');
    let id_conversation = document.getElementById('id_conversation');
    let id_envoyeur = document.getElementById('id_envoyeur');
    let dernierMessage = divConversation.lastElementChild;
    let dernierMessageId;

    if (dernierMessage) {
        dernierMessageId = dernierMessage.id;
    }
    else {
        dernierMessageId = 0;
    }

    let response = await fetch('/api/messages/' + id_conversation.value + '/' + dernierMessageId, {
        method: 'GET',
        headers: {
            'Accept': 'application/json; charset=utf-8',
            'Content-Type': 'application/json; charset=utf-8',
            'Authorization': `Bearer ${getCookie('TokenAPI')}`
        }
    });

    let data = await response.json();

    if (data['data']) {
        data['data'].forEach((message) => {
            creerMessage(message.envoyeur.id == id_envoyeur.value, message);
        });
    }
    else {
        alertErreurs(data);
    }

    setTimeout(getNewMessages, 1000);
}

async function getUpdatedMessages() {
    let id_conversation = document.getElementById('id_conversation');

    let response = await fetch('/api/messages/updated/' + id_conversation.value + '/' + date_derniere_update, {
        method: 'GET',
        headers: {
            'Accept': 'application/json; charset=utf-8',
            'Content-Type': 'application/json; charset=utf-8',
            'Authorization': `Bearer ${getCookie('TokenAPI')}`
        }
    });

    let data = await response.json();

    if (data['data']) {
        date_derniere_update = new Date().toLocaleString('sv-SE');

        data['data'].forEach((message) => {
            let divRowToUpdate = document.getElementById(message.id);

            if (divRowToUpdate) {
                if (message.date_heure_supprime) {
                    divRowToUpdate.remove();
                }
                else {
                    let pMessageToUpdate = divRowToUpdate.lastElementChild.lastElementChild.firstElementChild;
                    pMessageToUpdate.innerHTML = message.texte;
                }
            }
        });
    }
    else {
        alertErreurs(data);
    }

    setTimeout(getUpdatedMessages, 1000);
}

async function actionMessage(event) {
    if (event) {
        event.preventDefault();
    }

    let pieceJointe = document.getElementById('pieceJointe');
    let texte = document.getElementById('texte');
    let id_conversation = document.getElementById('id_conversation');
    let id_message = document.getElementById('id_message');
    let action = document.getElementById('action');

    texte.focus();

    if (action.value == 'POST') {
        texte.value = texte.value.trim();

        if (texte.value == '') {
            return;
        }

        let messageData = new FormData();

        if (pieceJointe.files.length > 0) {
            messageData.append('pieceJointe', pieceJointe.files[0]);
        }

        messageData.append('texte', texte.value);
        messageData.append('id_conversation', id_conversation.value);

        let response = await fetch('/api/messages', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${getCookie('TokenAPI')}`
            },
            body: messageData
        });

        let data = await response.json();

        if (data['SUCCÈS']) {
            creerMessage(true, data['message']);
            texte.value = '';
            pieceJointe.value = '';
        }
        else {
            alertErreurs(data);
        }
    }
    else if (action.value == 'PUT') {
        let response = await fetch('/api/messages/' + id_message.value, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json; charset=utf-8',
                'Content-Type': 'application/json; charset=utf-8',
                'Authorization': `Bearer ${getCookie('TokenAPI')}`
            },
            body: JSON.stringify({
                'texte' : texte.value
            })
        });

        let data = await response.json();

        if (data['SUCCÈS']) {
            let divRow = document.getElementById(id_message.value);
            let pMessage = divRow.lastElementChild.lastElementChild.firstElementChild;

            pMessage.innerHTML = texte.value;

            envoyerMessage();
        }
        else {
            alertErreurs(data);
        }
    }
    else if (action.value == 'DELETE') {
        let response = await fetch('/api/messages/' + id_message.value, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json; charset=utf-8',
                'Content-Type': 'application/json; charset=utf-8',
                'Authorization': `Bearer ${getCookie('TokenAPI')}`
            }
        });

        envoyerMessage();

        let data = await response.json();

        if (data['SUCCÈS']) {
            let divRow = document.getElementById(id_message.value);
            if (divRow) {
                divRow.remove();
            }
        }
        else {
            alertErreurs(data);
        }
    }
}

function creerMessage(isEnvoyeur, message) {
    if (document.getElementById(message.id)) {
        return;
    }

    let divConversation = document.getElementById('divConversation');

    if (!divConversation) {
        return;
    }

    let divRow = document.createElement('div');
    divRow.id = message.id;
    divRow.classList.add('flex');
    divRow.classList.add('items-center');
    if (isEnvoyeur) {
        divRow.classList.add('justify-end');
    }
    else {
        divRow.classList.add('justify-start');
    }

    divConversation.insertAdjacentElement('beforeend', divRow);

    if (isEnvoyeur) {
        let divBoutons = document.createElement('div');
        divBoutons.classList.add('grid');
        divBoutons.classList.add('gap-4');

        divRow.insertAdjacentElement('afterbegin', divBoutons);

        let imgEdit = document.createElement('img');
        imgEdit.classList.add('h-4');
        imgEdit.classList.add('boutonModifierMessage');
        imgEdit.src = '../img/edit.svg';
        imgEdit.alt = 'Modifier';
        imgEdit.addEventListener('click', modifierMessage);

        divBoutons.insertAdjacentElement('afterbegin', imgEdit);

        let imgDelete = document.createElement('img');
        imgDelete.classList.add('h-4');
        imgDelete.classList.add('boutonSupprimerMessage');
        imgDelete.src = '../img/delete.svg';
        imgDelete.alt = 'Supprimer';
        imgDelete.addEventListener('click', supprimerMessage);

        divBoutons.insertAdjacentElement('beforeend', imgDelete);
    }

    let divMessage = document.createElement('div');
    divMessage.classList.add('w-1/2');
    divMessage.classList.add('m-4');

    divRow.insertAdjacentElement('beforeend', divMessage);

    let pCreatedAt = document.createElement('p');
    if (isEnvoyeur) {
        pCreatedAt.classList.add('text-right');
    }
    else {
        pCreatedAt.classList.add('text-left');
    }
    pCreatedAt.innerHTML = new Date().toLocaleString('sv-SE');

    divMessage.insertAdjacentElement('afterbegin', pCreatedAt);

    let divContenuMessage = document.createElement('div');
    divContenuMessage.classList.add('break-words');
    if (isEnvoyeur) {
        divContenuMessage.classList.add('bg-[#18B7BE]');
    }
    else {
        divContenuMessage.classList.add('bg-[#178CA4]');
    }
    divContenuMessage.classList.add('p-6');
    divContenuMessage.classList.add('rounded-xl');
    divContenuMessage.classList.add('text-white');
    divContenuMessage.classList.add('text-justify');

    divMessage.insertAdjacentElement('beforeend', divContenuMessage);

    let pMessage = document.createElement('p');
    pMessage.innerHTML = message.texte;
    divContenuMessage.insertAdjacentElement('afterbegin', pMessage);

    if (message.chemin_du_fichier) {
        divContenuMessage.insertAdjacentElement('beforeend', creerPieceJointe(message));
    }

    divConversation.scrollTop = divConversation.scrollHeight;
}

function creerPieceJointe(message) {
    let nomFichier = message.chemin_du_fichier.split('/').pop();
    let extension = nomFichier.split('.').pop();
    let supportedImagesExtensions = [ 'jpg', 'jpeg', 'png' ];

    if (supportedImagesExtensions.includes(extension)) {
        let imgPieceJointe = document.createElement('img');
        imgPieceJointe.classList.add('max-h-80');
        imgPieceJointe.classList.add('mx-auto');
        imgPieceJointe.src = message.chemin_du_fichier;
        imgPieceJointe.alt = nomFichier;
        return imgPieceJointe;
    }
    else {
        let aPieceJointe = document.createElement('a');
        aPieceJointe.classList.add('block');
        aPieceJointe.classList.add('bg-white');
        aPieceJointe.classList.add('hover:bg-slate-100');
        aPieceJointe.classList.add('text-black');
        aPieceJointe.classList.add('rounded');
        aPieceJointe.classList.add('p-4');
        aPieceJointe.classList.add('mt-2');
        aPieceJointe.href = message.chemin_du_fichier;
        aPieceJointe.target = '_blank';
        aPieceJointe.innerHTML = nomFichier;
        return aPieceJointe;
    }
}

async function filtrerProfils(event) {
    if (event) {
        event.preventDefault();
    }

    let courriel = document.getElementById('filtreCourriel');
    let action = document.getElementById('action');
    let rolesUserLoggedIn = JSON.parse(document.getElementById('rolesUserLogged').value);

    userIsAdmin = false;

    rolesUserLoggedIn.forEach(function(role) {
        if (userIsAdmin == false && role.role == "Administrateur") {
            userIsAdmin = true
        }
    });

    courriel.focus();

    if (action.value == 'GET') {
        courriel.value = courriel.value.trim();

        if (courriel.value == '') {
            return;
        }

        let response = await fetch ('/api/profilesApi/' + courriel.value, {
            method: 'GET',
            headers: {
                'Accept': 'application/json; charset=utf-8',
                'Content-Type': 'application/json; charset=utf-8',
                'Authorization': `Bearer ${getCookie('TokenAPI')}`
            }
        });

        let data = await response.json();

        if (!data['ERREUR']) {
            updatePageProfiles(data, userIsAdmin);
        } else {
            alert('Aucun utilisateur avec ce courriel');
        }
    }
}

async function updatePageProfiles(data, isAdmin) {
    let tbody = document.getElementById('tbodyProfiles');

    needAdmin = false;

    data['data']['0'].roles.forEach(function(roles) {
        if(needAdmin == false && (roles.role == "Administrateur" || roles.role == "Employé")) {
            needAdmin = true;
        }
    });

    if ((needAdmin && isAdmin) || !needAdmin) {
        while (tbody.firstChild) {
            tbody.removeChild(tbody.lastChild);
        }

        ligne = document.createElement("tr");
        ligne.classList.add("bg-[#FFFFFF]");
        tbody.appendChild(ligne);

        colonne = document.createElement("td");
        colonne.classList.add("pt-5", "pb-5", "border-2", "border-solid");
        texteColonne = document.createTextNode(data['data']['0'].prenom);
        colonne.appendChild(texteColonne);
        ligne.appendChild(colonne)

        colonne = document.createElement("td");
        colonne.classList.add("pt-5", "pb-5", "border-2", "border-solid");
        texteColonne = document.createTextNode(data['data']['0'].nom);
        colonne.appendChild(texteColonne);
        ligne.appendChild(colonne)

        colonne = document.createElement("td");
        colonne.classList.add("pt-5", "pb-5", "border-2", "border-solid");
        texteColonne = document.createTextNode(data['data']['0'].email);
        colonne.appendChild(texteColonne);
        ligne.appendChild(colonne)

        colonne = document.createElement("td");
        colonne.classList.add("pt-5", "pb-5", "border-2", "border-solid");
        texteColonne = document.createTextNode(data['data']['0'].telephone);
        colonne.appendChild(texteColonne);
        ligne.appendChild(colonne)

        colonne = document.createElement("td");
        colonne.classList.add("pt-5", "pb-5", "border-2", "border-solid");

        form = document.createElement("form");
            form.setAttribute("method", "GET");
            form.setAttribute("action", "../profile/user")
            input = document.createElement("input");
                input.setAttribute("type", "hidden");
                input.setAttribute("value", data['data']['0'].id);
                input.setAttribute("name", "id_user");
            button = document.createElement("button");
                button.classList.add("bouton");
                    texteButton = document.createTextNode("Voir ce profil");
                button.appendChild(texteButton);
        form.appendChild(input);
        form.appendChild(button);

        colonne.appendChild(form);
        ligne.appendChild(colonne);

        tbody.appendChild(ligne);
    } else {
        alert('Vous n\'êtes pas autorisé à voir ce profil.');
    }
}

async function supprimerConversation(event) {
    if(!confirm('Êtes-vous certains de vouloir supprimer cette conversation?')) {
        return;
    }

    let conversation = event.currentTarget.parentElement;

    let response = await fetch('/api/conversation/' + conversation.id, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json; charset=utf-8',
            'Content-Type': 'application/json; charset=utf-8',
            'Authorization': `Bearer ${getCookie('TokenAPI')}`
        }
    });

    let data = await response.json();

    if (data['SUCCÈS']) {
        conversation.remove();
    }
    else {
        alertErreurs(data);
    }
}

async function approuverPret(e) {
    e.preventDefault();

    let id = document.getElementById('id_demande').value;
    let taux = document.getElementById('taux').value;
    let duree = document.getElementById('duree').value;

    let response = await fetch('/api/creation/pret', {
        method: 'POST',
        headers: {
            'Accept': 'application/json; charset=utf-8',
            'Content-Type': 'application/json; charset=utf-8',
            'Authorization': `Bearer ${getCookie('TokenAPI')}`
        },
        body: JSON.stringify({
            'id_demande' : id,
            'taux_interet' : taux,
            'duree' : duree,
        })
    });

    let data = await response.json();

    if (!data['SUCCES']) {
        if (data['ERREUR'])
            alertErreurs(data);
        if (data['NOTE'])
            alert("Cette demande a déjà été traitée.")
    }
    else {
        alert("La demande a été approuvée.");
        window.location.href = "/demandesDePret";
    }
}

async function refuserPret(e) {
    e.preventDefault();

    let id = document.getElementById('id_demande').value;
    let raison = document.getElementById('raison').value;
    let montant = document.getElementById('montant').value;

    let response = await fetch('/api/modification/demande_de_pret', {
        method: 'POST',
        headers: {
            'Accept': 'application/json; charset=utf-8',
            'Content-Type': 'application/json; charset=utf-8',
            'Authorization': `Bearer ${getCookie('TokenAPI')}`
        },
        body: JSON.stringify({
            'id' : id,
            'raison' : raison,
            'montant' : montant,
            'id_etat_demande' : 2
        })
    });

    let data = await response.json();

    if (!data['SUCCES']) {
        if (data['ERREUR'])
            alertErreurs(data);
        if (data['NOTE'])
            alert("Cette demande a déjà été traitée.")
    }
    else {
        alert("La demande a été refusée.");
        window.location.href = "/demandesDePret";
    }
}
