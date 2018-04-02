<div class="cntr">
    <div id="messageTest" class="testMessage col-sm-5">
        <?if(!empty($message['test'])):?>
            <div class="alert alert-primary" role="alert" >
                <?=$message['test']?>
            </div>
        <?endif;?>
    </div>
    <h3>Вы ответили на все вопросы в этой теме.</h3>
    <h3>Отвечено <?=$count?> вопросов</h3>
    <?if($ratio > 75):?>
    <h2>Вы сдали!</h2>
    <?else:?>
    <h2>Вы не сдали!</h2>
    <?endif?>
    <h3><?=$ratio?>%</h3>
</div>
<!--Вопросы: 3 из 3 (100%)-->
<br>
Верно: <?=$right?> из <?=$count?>:  (<?=$ratio?>%)