document.addEventListener('DOMContentLoaded', function(ev){
    var radios = document.querySelectorAll("input[name='activeInactivAll']")
    var tableContainer = document.querySelector('.table-container');
    radios.forEach(function (radio) {
        radio.addEventListener('click', function(ev) {
            var value = ev.target.value;
            $.ajax({
                url: window.location.href + '/filter',
                type: 'POST',
                data: {filter: value},
                success: function (data, status) {
                    tableContainer.innerHTML = data;
                }, error: function () {
                }
            });
        })
    });
})