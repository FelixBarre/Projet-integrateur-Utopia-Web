let formSelect = document.getElementById('formSelect');
let selectValue = document.getElementById('selectValue');
let formDate = document.getElementById("formDate");
let dateDebut = document.getElementById("date_debut");
let dateFin = document.getElementById("date_fin");
let errorClass = document.getElementById('error-class');

console.log(errorClass);

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
