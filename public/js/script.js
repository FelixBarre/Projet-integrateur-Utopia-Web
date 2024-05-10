var date_derniere_update = new Date().toLocaleString('sv-SE');

window.onload = function() {
    pageAccueil();
    pageConversation();
    pageShowProfils();
    pageConversations();
    pagePret();
}

async function pageAccueil() {
    let formSelect = document.getElementById('formSelect');

    if(!formSelect) {
        return;
    }

    let selectValue = document.getElementById('selectValue');

    selectValue.addEventListener('change', async function(){

        const selectedValue = this.value;


        try{
            let response = await fetch("/api/transactions/filter/" + selectedValue , {
                method: 'GET',
                headers: {
                    'Accept': 'application/json; charset=utf-8',
                    'Content-Type': 'application/json; charset=utf-8'
                },

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

            let tbody = document.getElementById("detailsTransaction");

            let tr = document.createElement("tr")
            tr.classList.add("w-full");

            let tdID = document.createElement("td");
            tdID.classList.add("p-5", "m-auto", "text-center", "bg-white", "border-2", "border-solid");
            tdID.textContent = transaction.id;

            let tdOperation = document.createElement("td");
            tdOperation.classList.add("p-5", "m-auto", "text-center", "bg-white", "border-2", "border-solid");
            tdOperation.textContent = transaction.type_transactions.label;

            let tdNom = document.createElement("td");
            tdNom.classList.add("p-5", "m-auto", "text-center", "bg-white", "border-2", "border-solid");
            if(transaction.id_compte_envoyeur==null){
                tdNom.textContent = transaction.id_compte_receveur;
            }else{
                tdNom.textContent = transaction.id_compte_envoyeur;
            }


            let tdEmail = document.createElement("td");
            tdEmail.classList.add("p-5", "m-auto", "text-center", "bg-white", "border-2", "border-solid");
            tdEmail.textContent = "";

            let tdDate = document.createElement("td");
            tdDate.classList.add("p-5", "m-auto", "text-center", "bg-white", "border-2", "border-solid");
            tdDate.textContent = transaction.created_at;

            let tdStatus = document.createElement("td");
            tdStatus.classList.add("p-5", "m-auto", "text-center", "bg-white", "border-2", "border-solid");
            tdStatus.textContent = transaction.id_etat_transaction;

                tr.appendChild(tdID);
                tr.appendChild(tdOperation);
                tr.appendChild(tdNom);
                tr.appendChild(tdEmail);
                tr.appendChild(tdDate);
                tr.appendChild(tdStatus);
                tbody.appendChild(tr);


            });


        } catch(error){
            console.log(error);
            alert('une erreur est survenue lors de la requête.');
        }

    });



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
    let texte = document.getElementById('texte');
    let action = document.getElementById('action');

    boutonActionMessage.innerHTML = 'Envoyer';
    id_message.value = '';
    texte.value = '';
    texte.focus();
    action.value = 'POST';
}

function modifierMessage(event) {
    let boutonModifierMessage = event.currentTarget;
    let divRow = boutonModifierMessage.parentElement.parentElement;
    let pMessage = divRow.lastElementChild.lastElementChild;
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
            'Content-Type': 'application/json; charset=utf-8'
        }
    });

    let data = await response.json();

    if (data['data']) {
        data['data'].forEach((message) => {
            creerMessage(message.envoyeur.id == id_envoyeur.value, message.texte, message.id);
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
            'Content-Type': 'application/json; charset=utf-8'
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
                    let pMessageToUpdate = divRowToUpdate.lastElementChild.lastElementChild;
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

    let texte = document.getElementById('texte');
    let id_envoyeur = document.getElementById('id_envoyeur');
    let id_receveur = document.getElementById('id_receveur');
    let id_conversation = document.getElementById('id_conversation');
    let id_message = document.getElementById('id_message');
    let action = document.getElementById('action');

    texte.focus();

    if (action.value == 'POST') {
        texte.value = texte.value.trim();

        if (texte.value == '') {
            return;
        }

        let response = await fetch('/api/messages', {
            method: 'POST',
            headers: {
                'Accept': 'application/json; charset=utf-8',
                'Content-Type': 'application/json; charset=utf-8'
            },
            body: JSON.stringify({
                'texte' : texte.value,
                'id_envoyeur' : id_envoyeur.value,
                'id_receveur' : id_receveur.value,
                'id_conversation' : id_conversation.value
            })
        });

        let data = await response.json();

        if (data['SUCCÈS']) {
            creerMessage(true, texte.value, data['id']);
            texte.value = '';
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
                'Content-Type': 'application/json; charset=utf-8'
            },
            body: JSON.stringify({
                'texte' : texte.value
            })
        });

        let data = await response.json();

        if (data['SUCCÈS']) {
            let divRow = document.getElementById(id_message.value);
            let pMessage = divRow.lastElementChild.lastElementChild;

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
                'Content-Type': 'application/json; charset=utf-8'
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

function creerMessage(isEnvoyeur, texte, idMessage) {
    if (document.getElementById(idMessage)) {
        return;
    }

    let divConversation = document.getElementById('divConversation');

    if (!divConversation) {
        return;
    }

    let divRow = document.createElement('div');
    divRow.id = idMessage;
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
        imgEdit.src = 'http://localhost:8000/img/edit.svg';
        imgEdit.alt = 'Modifier';
        imgEdit.addEventListener('click', modifierMessage);

        divBoutons.insertAdjacentElement('afterbegin', imgEdit);

        let imgDelete = document.createElement('img');
        imgDelete.classList.add('h-4');
        imgDelete.classList.add('boutonSupprimerMessage');
        imgDelete.src = 'http://localhost:8000/img/delete.svg';
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

    let pMessage = document.createElement('p');
    pMessage.classList.add('break-words');
    if (isEnvoyeur) {
        pMessage.classList.add('bg-[#18B7BE]');
    }
    else {
        pMessage.classList.add('bg-[#178CA4]');
    }
    pMessage.classList.add('p-6');
    pMessage.classList.add('rounded-xl');
    pMessage.classList.add('text-white');
    pMessage.classList.add('text-justify');
    pMessage.innerHTML = texte;

    divMessage.insertAdjacentElement('beforeend', pMessage);

    divConversation.scrollTop = divConversation.scrollHeight;
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
                'Content-Type': 'application/json; charset=utf-8'
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
        colonne.classList.add("pt-5");
        colonne.classList.add("pb-5");
        colonne.classList.add("border-2");
        colonne.classList.add("border-solid");
        texteColonne = document.createTextNode(data['data']['0'].prenom);
        colonne.appendChild(texteColonne);
        ligne.appendChild(colonne)

        colonne = document.createElement("td");
        colonne.classList.add("pt-5");
        colonne.classList.add("pb-5");
        colonne.classList.add("border-2");
        colonne.classList.add("border-solid");
        texteColonne = document.createTextNode(data['data']['0'].nom);
        colonne.appendChild(texteColonne);
        ligne.appendChild(colonne)

        colonne = document.createElement("td");
        colonne.classList.add("pt-5");
        colonne.classList.add("pb-5");
        colonne.classList.add("border-2");
        colonne.classList.add("border-solid");
        texteColonne = document.createTextNode(data['data']['0'].email);
        colonne.appendChild(texteColonne);
        ligne.appendChild(colonne)

        colonne = document.createElement("td");
        colonne.classList.add("pt-5");
        colonne.classList.add("pb-5");
        colonne.classList.add("border-2");
        colonne.classList.add("border-solid");
        texteColonne = document.createTextNode(data['data']['0'].telephone);
        colonne.appendChild(texteColonne);
        ligne.appendChild(colonne)

        colonne = document.createElement("td");
        colonne.classList.add("pt-5");
        colonne.classList.add("pb-5");
        colonne.classList.add("border-2");
        colonne.classList.add("border-solid");

        form = document.createElement("form");
            form.setAttribute("method", "GET");
            form.setAttribute("action", "http://localhost:8000/profile/user")
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
            'Content-Type': 'application/json; charset=utf-8'
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
            'Content-Type': 'application/json; charset=utf-8'
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
            'Content-Type': 'application/json; charset=utf-8'
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
