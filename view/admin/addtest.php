<?php
/**
 * Страница добавления тестов в базу
 */
?>
<style>
    input[type="radio"] + input[type="radio"] {
        display: none;
    }
</style>
<div class='cntr'>
<h1>Добавление тестов</h1>
    <form method="POST" enctype="multipart/form-data" style="text-align: left;">
        <label>
            <input checked type="radio" id="theme" value="new">
            <input type="text" placeholder="new" name="code[]">
            <input type="text" placeholder="Новая" name="name[]">
        </label><br>
            <?
                foreach ($theme as $item):
            ?>
                    <label>
                        <input type="radio" name="code[]" value="<?=$item['theme_code']?>">
                        <input type="radio" name="name[]" value="<?=$item['theme_text']?>">
                        <?=$item['theme_code']?> - <?=$item['theme_text']?>
                    </label><br>
            <?
                endforeach;
            ?>
        </input>
        <input type="file" name="file">
        <textarea name="text"></textarea>
        <input type="submit" value="отправить">
    </form>
    <script>
        $(function () {
            $('#theme').parent().on('click',function () {
                $(':checked').prop("checked",false);
                $("#theme").prop("checked",true);
                $("input[type='text']").removeProp('disabled');
            });
            $("input[type='text']").on("input",function () {
                if($(this).val().length > 0){
                    $(":radio").prop('disabled',true);
                }
                else{
                    $(":radio").prop("disabled",false);
                }
            });
            $(":radio").on("click",function () {
                $("#theme").prop("checked",false);
                $("input[type='text']").prop("disabled",true);
                $(this).next().prop("checked",true);
            })
        })
    </script>
</div>