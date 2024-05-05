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
    else if (data['ÉCHEC']) {
        alert(data['ÉCHEC']);
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
        let valeurTexte = texte.value.trim();

        texte.value = '';

        if (valeurTexte == '') {
            return;
        }

        let response = await fetch('/api/messages', {
            method: 'POST',
            headers: {
                'Accept': 'application/json; charset=utf-8',
                'Content-Type': 'application/json; charset=utf-8'
            },
            body: JSON.stringify({
                'texte' : valeurTexte,
                'id_envoyeur' : id_envoyeur.value,
                'id_receveur' : id_receveur.value,
                'id_conversation' : id_conversation.value
            })
        });

        let data = await response.json();

        if (data['SUCCÈS']) {
            creerMessage(true, valeurTexte, data['id']);
        }
        else if (data['ÉCHEC']) {
            alert(data['ÉCHEC']);
        }
    }
    else if (action.value == 'PUT') {
        if (data['SUCCÈS']) {

        }
        else if (data['ÉCHEC']) {
            alert(data['ÉCHEC']);
        }
    }
}

function creerMessage(isEnvoyeur, texte, idMessage) {
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
