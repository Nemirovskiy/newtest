<?php
/**
 * Страница добавления тестов в базу
 */
?>
<div class='cntr'>
<h1>Добавление тестов</h1>
    <form method="POST" enctype="multipart/form-data" style="text-align: left;">
        <label>
            <input checked type="radio" name="code" id="theme" value="new">
            <input type="text" placeholder="new" name="newCode">
            <input type="text" placeholder="Новая" name="newName">
        </label><br>
            <?
                foreach ($theme as $item):
            ?>
                    <label>
                        <input type="radio" name="code" value="<?=$item['theme_code']?>">
                        <?=$item['theme_code']?> - <?=$item['theme_text']?>
                        <input type="hidden" name="name" value="<?=$item['theme_text']?>">
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
            $("input[type='text']").on("click",function () {
                $("#theme").prop("checked",true);
            })
        })
    </script>
</div>