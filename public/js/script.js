let formSelect = document.getElementById('formSelect');
let selectValue = document.getElementById('selectValue');

selectValue.addEventListener('change', function(){
    formSelect.submit();
});
