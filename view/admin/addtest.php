<?php
/**
 * Страница добавления тестов в базу
 */
?>

<? if(!empty($message)): ?>
    <h4 style="color: blue;"><?=$message?></h4>
<?endif;?>
<? if(!empty($errors)): ?>
    <div class="modal fade" id="errors" tabindex="-1"
         role="dialog" aria-labelledby="errors" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Ошибка</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body alert alert-danger" role="alert">
                    <?=$errors?>
                </div>
            </div>
        </div>
    </div>
    <h4 style="color: red;"></h4>
    <script>
        $('#errors').modal();
    </script>
<?endif;?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#addtest" role="tab" aria-controls="home" aria-selected="true">Добавление тестов</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="addtest" role="tabpanel" aria-labelledby="home-tab">
        <form class="form-group row mt-3" method="POST" enctype="multipart/form-data" id="addForm">
            <label class="col-sm-2 col-form-label" for="inputTheme">Тема</label>
            <select id="inputTheme" name="addCode" class="form-control col-sm-3">
                <option selected value="new">Новая</option>
                <? foreach ($theme as $item): ?>
                    <option value="<?=$item['code']?>"><?=$item['text']?></option>
                <? endforeach; ?>
            </select>
            <div id="newTheme" class="col-sm-7 row">
                <div class="col-sm-5 col">
                    <input type="text" class="form-control" name="newCode" id="newThemeCode" placeholder="код темы">
                </div>
                <div class="col-sm-7 col">
                    <input type="text" class="form-control" name="newName" id="newThemeName" placeholder="Название темы">
                </div>
            </div>
            <div class="col-sm-12">
                <p class="textNewTheme mb-1 text-center" id="textNewTeme"></p>
            </div>
            <div class="col-sm-8 row mt-0 mb-2 mx-auto">
                <div class="col-sm">
                    <label class="btn btn-outline-primary" for="buttonFile">Выберите файл</label>
                    <input style="display: none;" type="file" name="file"  accept="text/plain" id="buttonFile">
                </div>
                <div class="col-sm">
                    <input type="button" id="buttonText" name="onText" class="btn btn-outline-primary" value="Добавить текст">
                </div>
            </div>
            <div class="col-sm-12">
                <p class="textNewTheme mb-1 text-center" id="textFileTeme"></p>
                <textarea disabled style="display: none" style="overflow-x: hidden; overflow-y: visible;" name="text" id="textArea" class="form-control" ></textarea>
            </div>
            <div class="col-sm-10 row  mx-auto mt-2">
                <input type="submit" id="buttonAddTest" name="addTest"
                       class="btn btn-outline-primary col-sm-12" value="Добавить тест">
            </div>
        </form>

    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
</div>

<script>
    $(function () {
        $("#inputTheme").on("input",function () {
            if($(this).val() === 'new'){
                $('#newTheme').show(500);
                $('#newTheme input').prop('disabled',false);
                $('#textNewTeme').text('');
            }
            else {
                $('#newTheme').hide(500);
                $('#newTheme input').prop('disabled',true);
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
                $('#buttonText').val('Добавить текст');
                $('#buttonFile').prop('disabled',false);
                $('[for=\'buttonFile\']').removeClass('disabled');
            }
            else{
                $('#textArea').prop('disabled',false);
                $('#buttonText').val('Убрать текст');
                $('#buttonFile').prop('disabled',true);
                $('[for=\'buttonFile\']').addClass('disabled');
                $('#textFileTeme').text('');
            }
        });
        $('#buttonFile').on('change',function () {
            $('#textFileTeme').text('Вставим тесты из файла ' + $(this).val().split("\\").pop());
        });
        $('#addForm').on('submit',function (e) {
            var flag = true;
            $('#addForm input, #addForm select, #addForm textarea')
                .not(':disabled, [type=\'submit\'], [type=\'submit\']').each(function () {
                if($(this).val().length < 1){
                    $(this).addClass('is-invalid');
                    $(this).prev('label').addClass('invalid-feedback');
                }else{
                    $(this).removeClass('is-invalid');
                    $(this).prev('label').removeClass('invalid-feedback');
                }
                if($(this).val().length < 1 && flag)
                    flag = false;
            })
            if(!flag)
                e.preventDefault();
        })
    })
</script>
</div>