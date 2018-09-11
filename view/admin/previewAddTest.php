<h3>
    Добавляем тесты в тему  <?=$theme['name']?>
</h3>

<ul class="list-unstyled">
    <?if(is_array($addTests)) foreach ($addTests as $quest):?>
        <li class="quest  bg-light mt-1">
            <b class="quest-number">№ <?=$quest['number']?> </b>
            <span class="quest-text"> <?=$quest['quest']?></span></li>
        <ul>
            <? foreach($quest['answers'] as $answer): ?>
                <li class="ml-3 <?if($answer['right']) echo "text-success"?>" >
                    <?=$answer['text']?>
                </li>
            <? endforeach;?>
        </ul>
    <? endforeach; ?>
</ul>
<form class="col-sm-12 row mx-auto text-center justify-content-center" method="POST">
    <? if(empty($errors)): ?>
        <button class="btn m-2 col-sm  btn-success" name="submit">Добавить <?=$count?> вопросов</button>
    <?endif;?>
    <button class="btn m-2 col-sm btn-warning" name="reset">Отмена</button>

</form>

