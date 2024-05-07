window.onload = function() {
    pageAccueil();
    pageConversation();
}

function pageAccueil() {
    let formSelect = document.getElementById('formSelect');

    if(!formSelect) {
        return;
    }

    let selectValue = document.getElementById('selectValue');

    selectValue.addEventListener('change', function(){
        formSelect.submit();
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

    getNewMessages();
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

    let response = await fetch('/api/messages/' + id_conversation.value + '/' + dernierMessage.id, {
        method: 'GET',
        headers: {
            'Accept': 'application/json; charset=utf-8',
            'Content-Type': 'application/json; charset=utf-8'
        }
    });

    let data = await response.json();

    if (data['data']) {
        data['data'].forEach(message => {
            creerMessage(message.id_envoyeur == id_envoyeur.value, message.texte, message.id);
        });
    }
    else {
        alertErreurs(data);
    }

    setTimeout(getNewMessages, 1000);
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
        imgDelete.src = 'http://localhost:8000/img/delete.svg';
        imgDelete.alt = 'Supprimer';

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
