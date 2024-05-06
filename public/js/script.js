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

    getNewMessages();
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
        if (data['SUCCÈS']) {

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
    if (isEnvoyeur) {
        divRow.classList.add('justify-end');
    }
    else {
        divRow.classList.add('justify-start');
    }

    divConversation.insertAdjacentElement('beforeend', divRow);

    let divMessage = document.createElement('div');
    divMessage.classList.add('w-1/2');
    divMessage.classList.add('m-4');

    divRow.insertAdjacentElement('afterbegin', divMessage);

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
