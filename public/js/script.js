
let formSelect = document.getElementById('formSelect');
let selectValue = document.getElementById('selectValue');
let formDate = document.getElementById("formDate");
let dateDebut = document.getElementById("date_debut");
let dateFin = document.getElementById("date_fin");
let errorClass = document.getElementById('error-class');


selectValue.addEventListener('change', function(){
    formSelect.submit();
});

dateFin.addEventListener('change', function(event){
    if (!dateDebut.value) {
        event.preventDefault();
        errorClass.classList.remove('hidden');
    }else{
        formDate.submit();
    }

});

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
            actionMessage();
        }
    });
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

    if (action.value == 'POST') {
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
            creerMessage(true, texte.value);

            texte.value = '';
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

function creerMessage(isEnvoyeur, texte) {
    let divConversation = document.getElementById('divConversation');

    if (!divConversation) {
        return;
    }

    let divRow = document.createElement('div');
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
    pCreatedAt.innerHTML = '1970-01-01 01:01:01';

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

