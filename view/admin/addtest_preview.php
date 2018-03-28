<h3>
    Добавляем тесты в тему  <?=$theme['name']?>
</h3>
<? if(!empty($errors)): ?>
    <h4 style="color: red;"><?=$errors?></h4>
<?endif;?>
<? if(!empty($message)): ?>
    <h4 style="color: blue;"><?=$message?></h4>
<?endif;?>
<ul class="list">
    <?if(is_array($addTests)) foreach ($addTests as $quest):?>
        <li class="quest">
            <b class="quest-number">№ <?=$quest['number']?> </b>
            <span class="quest-text"> <?=$quest['quest']?></span></li>
        <? foreach($quest['answers'] as $answer): ?>
            <li class="<?if($answer['right']) echo "text-success"?>" >
                <?=$answer['text']?>
            </li>
        <? endforeach;?>
    <? endforeach; ?>
</ul>
<form class="col-sm-12 row mx-auto text-center justify-content-center" method="POST">
    <? if(empty($errors)): ?>
        <button class="btn m-2 col-sm  btn-success" name="submit">Добавить ?? вопросов</button>
    <?endif;?>
    <button class="btn m-2 col-sm btn-warning" name="reset">Отмена</button>

</form>

