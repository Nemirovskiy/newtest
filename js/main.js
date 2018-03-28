
// отображение нового вопроса
function generateNew(e) {
    var form = $('#main');
    form.html('');
    $('<h2/>').text('Вопрос № ' + e.number).appendTo(form);
    $('<h3/>').text(e.quest).appendTo(form);
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
    $('#submit').prop('disabled',false);
    var form = $('#main');
    form.html('');
    $('<h2/>').text('Вопрос № ' + e.number).appendTo(form);
    $('<h3/>').text(e.quest).appendTo(form);
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
    var main = $('#main');
    main.html('');
    $('<h3/>').text('Отвечены все вопросы в теме').appendTo(main);
    $('<p/>').text('Отвечено '+ e.used[e.code].length + ' вопросов').appendTo(main);
    $('<h4/>').text(e.ratio + '%').appendTo(main);
    $('#submit').prop('disabled', false);
}

//
function errorSubmit(d,e) {
    if(e !== 'parserror'){
        $('#main').removeClass('wait');
        $('#submit').val('Ок').prop('disabled',false);
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
    $('#main').removeClass('wait');
    $('#submit').val('Ок');
    if(s.wrong == 'true'){
        generateWrong(s);
    }else if(s.number > 0){
        generateNew(s);
    }else{
        generateResult(s);
    }
}

// отображение сообщений и ошибок если они есть
function showMessage(e) {
    if(e.errors.length > 0){
        $('<div/>',{
            'class': 'alert alert-danger',
            'role': 'alert'
        }).text(e.errors).appendTo('#message');
    }else if(e.message.length > 0){
        $('<div/>',{
            'class': 'alert alert-info',
            'role': 'alert'
        }).text(e.message).appendTo('#message');
    };
    setTimeout(function () {
        $(".testMessage .alert").alert('close');
    }, 5000);
}

// запуск программы
$(function () {
    // выключаем кнопку ответа
    $('#submit').prop('disabled', true);

    // скрываем сообщения через 5 сек
    setTimeout(function () {
        $(".testMessage .alert").alert('close');
    }, 5000);

    // сброс статистики
    $('#reset').on('click',function (e) {
        e.preventDefault();
        var code = $(this).val();
        var data = {
            "ajax": "ajax",
            "reset": code
        };
        $.ajax({
            method: 'POST',
            dataType: 'json',
            data: data,
            success: successSubmit
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
        var x = 0;
        //var e;
        var val = [];
        var code = null;
        $('#main').addClass('wait');
        $('#submit').prop('disabled',true).val('Загрузка...');
        $('input:checked').not(':disabled').each(function () {
            val.push($(this).val());
            code = $(this).prop('name').slice(0,-2);
            //alert(code);
        });
        var data = {
            "ajax": "ajax"
        };
        data[code] = val,
            //console.log(val);
            $.ajax({
                method: 'POST',
                dataType: 'json',
                data: data,
                timeout: 5000,
                success: successSubmit,
                error: errorSubmit
            })
    });

    /*function generateNew(e) {
        //console.log(e);
        var form = $('#main');
        form.html('');
        $('<h2/>').text('Вопрос № ' + e.number).appendTo(form);
        $('<h3/>').text(e.quest).appendTo(form);
        var list = $('<ul/>');
        e.answers.forEach(function (answ) {
            console.log(answ);
            var li = $('<li/>');
            var label = $('<label/>');
            $('<input/>').addClass('inpt').prop('name',e.code)
                .prop('type','checkbox').val(answ.order).appendTo(label);
            $('<p/>').text(answ.text).appendTo(label);
            //label.appendTo(li);
            //li.appendTo(list)
            list.append($('<li/>').append(label))
        });
        list.appendTo(form);
        //$('#submit').

    }
    function generateWrong(e) {
        var form = $('#main');
        form.html('');
        $('<h2/>').text('Вопрос № ' + e.number).appendTo(form);
        $('<h3/>').text(e.quest).appendTo(form);
        var list = $('<ul/>');
        e.answers.forEach(function (answ) {
            var li =$('<li/>');
            if(answ.right == 1)
                li.addClass('border border-success');
            if(answ.check === true)
                li.addClass('text-danger');
            li.text(answ.text).appendTo(list);
        });
        list.appendTo(form);
    }

    $('#submit').on('click',function (e) {
        e.preventDefault();
        var x = 0;
        //var e;
        var val = [];
        $('input:checked').not(':disabled').each(function () {
            val.push($(this).val());
        });
        var data = {
            "ajax": "ajax",
            "smp": val,
        }
        //console.log(val);
        $.ajax({
            method: 'POST',
            // url: '<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>',
            dataType: 'json',
            data: data,
            success: function (s) {
                // console.log(s);
                // console.log(JSON.parse(s));
                if(s.wrong == 'true'){
                    generateWrong(s);
                }else{
                    generateNew(s);
                }
            }
        })
    });*/
})