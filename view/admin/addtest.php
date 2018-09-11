<?php
/**
 * Страница добавления тестов в базу
 */
?>

<form class="form-group row mt-3" method="POST" enctype="multipart/form-data" id="addForm" novalidate>
    <div class="col-sm-12 row justify-content-center">
        <div class="col-sm-6 row">
            <label class="col col-form-label" for="inputTheme">Тема</label>
            <select id="inputTheme" name="addCode" class="col-8 form-control">
                <option selected value="new">Новая</option>
                <? foreach ($theme as $item): ?>
                    <option value="<?=$item['code']?>"><?=$item['text']?></option>
                <? endforeach; ?>
            </select>
        </div>
        <div id="newTheme" class="col-sm-6 row ml-sm-2 mt-2 mt-sm-0">
            <input required type="text" class="form-control col-4" name="newCode" id="newThemeCode" placeholder="код">
            <input required type="text" class="form-control col ml-1" name="newName" id="newThemeName" placeholder="название темы">
            <span class="invalid-feedback">
                Введите код и название новой темы
            </span>
        </div>
    </div>
    <div class="col-sm-12 mt-2">
        <p class="textNewTheme mb-1 text-center" id="textNewTeme"></p>
    </div>
    <div class="col-sm-10 input-group mt-0 mb-2 mx-auto justify-content-center" id="buttonGroup">
        <label class="btn col-md-5  btn-outline-primary form-control col-form-label" for="buttonFile">
            Выбрать файл
        </label>
        <label for="textArea" id="buttonText" class="btn col-md-5  btn-outline-primary form-control col-form-label">
            Вставить текст
        </label>
    </div>
    <div class="invalid-feedback  fileTextButton-feedback text-center">
        Выберите файл или вставте текст
    </div>
    <div class="col-sm-12">
        <p class="textNewTheme mb-1 text-center" id="textFileTeme"></p>
        <input required style="display: none;" type="file" name="file"  accept="text/plain" id="buttonFile">
        <textarea required disabled name="text" id="textArea" class="col-sm-12 form-control addText"
                  placeholder="<?="Образец:\n#1 ОСНОВОЙ АСЕПТИКИ ЯВЛЯЕТСЯ:\nа)"
                  ." дезинфекция;\nб) предстерилизационная очистка;\n"
                  ."в) стерилизация\n\n№2 ПОСЛЕ СНЯТИЯ ПЕРЧАТОК НЕОБХОДИМО:\n".
                  "а) провести гигиеническую обработку рук;\n".
                  "б) сполоснуть руки под проточной водой;\n".
                  "\n1 а\n2 а"

                  ?>"></textarea>
    </div>
    <button type="submit" id="buttonAddTest" name="addTest"
            class="btn btn-outline-primary col-sm-6 mx-auto">Подготовить к добавлению</button>
</form>


<script>
    /**
     * функция проверки формы добавления тестов
     * 1 - проверяем код-название, если выбрана новая тема
     * 2 - проверка наличия файла-текста
     *
     * @param e - событие отправки формы
     */
    function addFormValidate(e){
        /** 1. */
        if($('#inputTheme').val() === 'new'){
            $('#newTheme').addClass('was-validated');
        }
        /** проветить если есть значение при изменении текста-файла */

        if($('#addForm [type="file"]').val().length <= 0 && $('#addForm textarea').val().length <= 0 ){
            $('#buttonGroup label').addClass('btn-outline-danger');
            $('.fileTextButton-feedback').show();
        }
        /** если есть незаполенные поля - не отправлять форму */
        if($(this).find(':invalid').length > 0 ){
            e.preventDefault();
        }

    }

    $(function () {
        $('#addForm').on('submit',addFormValidate);

        $("#inputTheme").on("input",function () {
            if($(this).val() === 'new'){
                $('#newTheme input').prop('disabled',false).val('');
                $('#textNewTeme').text('Введите код и название новой темы');
            }
            else {
                $('#newTheme [name="newCode"]').val($(this).val()).prop('disabled',true);
                $('#newTheme [name="newName"]').val($(this.selectedOptions).text()).prop('disabled',true);
                $('#textNewTeme').text('Будет обновлена тема '+$("#inputTheme option:selected").text());
            }
        });

        $('#newTheme').on('input','input',function () {
            var name = $('#newThemeName').val();
            var code = $('#newThemeCode').val();
            if(name.length > 0 && code.length > 0 )
                $('#textNewTeme').text('Будет создана тема с кодом: '+
                    code + ' названием: ' + name);
            else
                $('#textNewTeme').text('');
        });

        $('#buttonText').on('click',function () {
            $('#textArea').toggle();
            if($('#textArea').is(':hidden')){
                $('#textArea').prop('disabled',true);
                $('#buttonText').text('Вставить текст');
                $('#buttonFile').val('').prop('disabled',false);
                $('[for=\'buttonFile\']').removeClass('disabled');
            }
            else{
                $('#textArea').prop('disabled',false).focus();
                $('#buttonText').text('Убрать текст');
                $('#buttonFile').prop('disabled',true);
                $('[for=\'buttonFile\']').addClass('disabled');
                $('#textFileTeme').text('');
                $('#buttonGroup label').removeClass('btn-outline-danger');
                $('.fileTextButton-feedback').hide();
            }
        });

        // вывод сообщения о выбранном файле
        $('#buttonFile').on('change',function () {
            $('#textFileTeme').text('Вставим тесты из файла ' + $(this).val().split("\\").pop());
            $('#buttonGroup label').removeClass('btn-outline-danger');
            $('.fileTextButton-feedback').hide();
        });
    });

</script>
</div>