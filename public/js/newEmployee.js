document.addEventListener('DOMContentLoaded', function(){
    Date.prototype.toDateInputValue = (function() {
        var local = new Date(this);
        local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
        return local.toJSON().slice(0,10);
    });
    document.getElementById('birthday').value = new Date().toDateInputValue();


    var form = document.getElementById('new-employee-form');
    var firstName = document.getElementById('first_name');
    var lastName = document.getElementById('last_name');
    var birthday = document.getElementById('birthday');
    var activeInactive = document.querySelectorAll('.activeInactive');
    var errorElement = document.getElementById('error-js');
    var chosenTitles = document.getElementById('chosen-titles');
    var title = document.getElementById('title');
    var successMsg = document.getElementById('success');

    form.addEventListener('submit', function(ev){
        if (successMsg != null) {
            successMsg.innerHTML = '';
            successMsg.classList.remove('active');
        }
        var messages = [];
        if (activeInactive[0].checked === false && activeInactive[1] === false) {
            messages.push('Employee must be set to active or inactive');
        }
        if (firstName.value === '' || firstName.value == null) {
            messages.push('First name is required');
        }
        if (lastName.value === '' || lastName.value == null) {
            messages.push('Last name is required');
        }
        if (birthday.value === '' || birthday.value == null) {
            messages.push('Birthday is required');
        }
        var isValidDate = Date.parse(birthday.value);

        if (isNaN(isValidDate)) {
            messages.push('Birthday is not in correct format');
        }


        var chosenTitlesSelect2 = document.querySelectorAll('.select2-selection__choice');
        var allTitles = [];
        chosenTitlesSelect2.forEach(function(el){
            allTitles.push(el.getAttribute('title'));
        })
        if (allTitles.length < 1) {
            messages.push('Title is required');
        }
        title.value=allTitles;

        if (messages.length > 0) {
            ev.preventDefault();
            errorElement.innerText = messages.join(', ');
        }

    });

    // var select2TitleInput = document.querySelector('.select2-search__field');
    // title.addEventListener('input', function(ev) {
    //     var titleValue = title.value;
    //     var parentEl = document.getElementById('title-response');
    //     parentEl.innerHTML = '';
    //     if (titleValue.length < 1) {
    //         return;
    //     }
    //     var newEmployeeNav = document.querySelector('a.new_employee_href');
    //     var res = newEmployeeNav.getAttribute('href');
    //
    //     $.ajax({
    //         url: res + '/search-titles',
    //         type: 'POST',
    //         data: {title: titleValue},
    //         success: function (data, status) {
    //             var el = document.createElement('div');
    //             el.innerHTML = data;
    //             parentEl.appendChild(el);
    //         }, error: function () {
    //         }
    //     });
    // })

    var titleContainer = document.getElementById('title-container');
    titleContainer.addEventListener('click', function(ev){
        var targetEl = ev.target;
        if (targetEl.classList.contains('ajax-response')) {
            var isNew = true;
            var chosenTitlesTokens = chosenTitles.innerText.split(',');
            chosenTitlesTokens.forEach(function(token){
                if(token === ev.target.id) {
                    isNew = false;
                }
            });
            if (isNew) {
                chosenTitles.innerText = chosenTitles.innerText + ev.target.id + ', ';
            }
        }
    });


    $('.js-example-basic-multiple').select2({
        placeholder: "ROLE_DEV,ROLE_HR.."
    });
    $('.js-example-basic-single').select2();


    var parentEl = document.getElementById('title-response');
    var newEmployeeNav = document.querySelector('a.new_employee_href');
    var res = newEmployeeNav.getAttribute('href');
    $('.js-data-example-ajax').select2({
        tags: false,
        multiple: true,
        tokenSeparators: [',', ' '],
        ajax: {
            url: res + '/search-titles',
            dataType: 'json',
            method: "POST",
            quietMillis: 50,
            delay: 250,
            data: function (params) {

                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.name
                        }
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 1
    });
});