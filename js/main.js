﻿// переменные необходимые для работы
var timeoutAlert; // номер таймаута
// отображение нового вопроса
function generateNew(e) {
    $('#submit').text('Далее').show();
    var form = $('#main');
    form.html('');
    $('<h2/>').text('Вопрос № ' + e.number).appendTo(form);
    $('<h3/>').html(e.quest).appendTo(form);
    var list = $('<div/>');
    list.addClass('col-sm');
    e.answers.forEach(function (answ) {
        var label = $('<label/>');
        $('<input/>').addClass('inpt').prop('name',e.code+'[]')
            .prop('type','checkbox').val(answ.order).appendTo(label);
        $('<p/>').html(answ.text).addClass('col').appendTo(label);
        label.addClass('row align-items-center');
        list.append(label);
    });
    list.appendTo(form);
}

// отображение правильного ответа
function generateWrong(e) {
    var form = $('#main');
    $('#submit').text('Продолжить').prop('disabled',false).blur();
    form.html('');
    $('<h2/>').text('Вопрос № ' + e.number).appendTo(form);
    $('<h3/>').html(e.quest).appendTo(form);
    var list = $('<ul/>').addClass('answerList');
    e.answers.forEach(function (answ) {
        var li =$('<li/>');
        if(answ.check === true)
            li.addClass(' answerCheck');
        if(answ.right == 1)
            li.addClass('text-success answerRight');
        li.html(answ.text).appendTo(list);
    });
    list.appendTo(form);
}

// функция ренерации результата тестирования
function generateResult(e){
    $('#submit').hide();
    var main = $('#main');
    main.html('');
    $('<h3/>').text('Отвечены все вопросы в теме').appendTo(main);
    $('<p/>').text('Отвечено '+ e.stat.choice + ' вопросов').appendTo(main);
    $('<h4/>').text(e.ratio + '%').appendTo(main);
}

//
function errorSubmit(d,e) {
    if(e === 'error'){
        $('#main').removeClass('wait');
        $('#submit').text('Продолжить').prop('disabled',false);
        $('<div/>',{
            'class': 'alert alert-danger',
            'role': 'alert'
        }).html('Ошибка соединения с сайтом<br>Попробуйте позже.').appendTo('#message');
        setTimeout(function () {
            $(".testMessage .alert").alert('close');
        }, 5000);
    }
}


// функция обработки ответа сервера на нажатие кнопки ответить
function successSubmit(s) {
    showMessage(s);
    showStat(s);
    $('#submit').text('Далее').blur();
    $('#main').removeClass('wait');
    if(s.wrong == true){
        generateWrong(s);
    }else if(parseInt(s.number) > 0){
        generateNew(s);
    }else{
        generateResult(s);
    }
}

// отображение сообщений и ошибок если они есть
function showMessage(e) {

    if(e.message.test.length > 0){
        clearTimeout(timeoutAlert);
        $('#messageTest').html('');
        $('<div/>',{
            'class': 'alert alert-info',
            'role': 'alert'
        }).text(e.message.test).appendTo('#messageTest');
    }else if(e.message.info.length > 0){
        clearTimeout(timeoutAlert);
        $('#message').html('');
        $('<div/>',{
            'class': 'alert alert-info',
            'role': 'alert'
        }).text(e.message.info).appendTo('#message');
    }else if(e.message.errors.length > 0){
        clearTimeout(timeoutAlert);
        $('#message').html('');
        $('<div/>',{
            'class': 'alert alert-danger',
            'role': 'alert'
        }).text(e.message.errors).appendTo('#message');
    };
    timeoutAlert = setTimeout(function () {
        $(".alert").alert('close');
    }, 5000);
}

// изменение статистики
function showStat(e) {
    $('.statAll').text(e.stat.all);
    $('.statRight').text(e.stat.right);
    $('.statChoice').text(e.stat.choice);
    $('.statRatioC').text(e.stat.ratioC);
    $('.statRatioR').text(e.stat.ratioR);
    $('.progress-bar.stat').css('width',e.stat.ratioC+'%').prop('aria-valuenow',e.stat.ratioC);
}

// отправка согласия
function sendAccept(){
    $.ajax({
        method: 'POST',
        dataType: 'json',
        data: { "accept": true, "ajax":true },
        success: successSubmit,
        error: errorSubmit
    })
}
// авторизация
function authUser(e){

    var data={ajax: true};
    if(this.checkValidity() === false){
        $(this).addClass('was-validated')
        e.preventDefault();
    }else{
        $('#auth input').each(
            function () {
                data[this.name] = this.value;
            }
        );
        $.ajax({
            method: 'POST',
            url: '/',
            dataType: 'json',
            data: data,
            timeout: 5000,
            success: authFin,
            error:   authFin
        })
    }
}
// финиш авторизации
function authFin(e){
    $('#loginModal').modal('hide');
    showMessage(e);
}

// запуск программы
$(function () {
    // выключаем кнопку ответа
    $('#submit').prop('disabled', true);

    // снимаем активность кнопки при нажатии
    // актуально на тач устройствах
    $('button').on('click',function () {
        $(this).prop('disabled',true);
        $(this).prop('disabled',false);
    });

    // скрываем сообщения через 5 сек
    setTimeout(function () {
        $(".testMessage .alert").alert('close');
    }, 5000);

    $('#accept').on('close.bs.alert',sendAccept);

    // сброс статистики
    $('#reset').on('click',function (e) {
        e.preventDefault();
        $(this).mouseout();
        var code = $(this).val();
        var data = {
            "ajax": "ajax",
            "reset": code
        };
        $.ajax({
            method: 'POST',
            dataType: 'json',
            data: data,
            success: successSubmit,
            error: errorSubmit
        })
    });

    // проверяем нажатие на ответы, если есть хоть
    // один ответ - включаем кнопку ответить, иначе - выключаем
    $('form').on('change','[type=\'checkbox\']',function() {
        if($('[type=\'checkbox\']:checked').length > 0)
            $('#submit').prop('disabled', false);
        else
            $('#submit').prop('disabled', true);
    });


    $('#submit').on('click',function (e) {
        e.preventDefault();
        $(this).mouseout();
        var x = 0;
        var val = [];
        var code = null;
        $('#main').addClass('wait');
        $('#submit').text('Загрузка...').prop('disabled',true);
        $('input:checked').not(':disabled').each(function () {
            val.push($(this).val());
            code = $(this).prop('name').slice(0,-2);
        });
        var data = {
            "ajax": "ajax"
        };
        data[code] = val,
            $.ajax({
                method: 'POST',
                dataType: 'json',
                data: data,
                timeout: 5000,
                success: successSubmit,
                error: errorSubmit
            })
    });

    /*** авторизация *********/
    $('#loginModal').on('shown.bs.modal',function (e) {
        $('#loginModal [name="login"]').focus();
    });
    $('#loginModal').on('show.bs.modal',function (e) {
        $('#auth').removeClass('was-validated');
    });

    $('#auth').on('submit',authUser)

})