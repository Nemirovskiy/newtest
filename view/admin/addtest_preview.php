<h3>
    Добавляем тесты в тему  <?=$theme['name']?>
</h3>
<? if(!empty($errors)): ?>
    <h4 style="color: red;"><?=$errors?></h4>
<?endif;?>
<? if(!empty($message)): ?>
    <h4 style="color: blue;"><?=$message?></h4>
<?endif;?>
<ul>
    <?if(is_array($addTests)) foreach ($addTests as $quest):?>
        <li class="quest">
            <b class="quest-number">№ <?=$quest['number']?> </b>
            <span class="quest-text"> <?=$quest['quest']?></span></li>
        <? foreach($quest['answers'] as $answer): ?>
            <li class="<?if($answer['right']) echo "r_otv"?>" >
                <?=$answer['text']?>
            </li>
        <? endforeach;?>
    <? endforeach; ?>
</ul>
<form method="POST">
    <? if(empty($errors)): ?>
        <button name="submit">Будем добавлять</button>
    <?endif;?>
    <button name="reset">Изменить</button>
</form>