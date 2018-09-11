<div id="messageTest" class="testMessage col-sm-5">
    <?if(!empty($message['test'])):?>
        <div class="alert alert-primary" role="alert" >
            <?=$message['test']?>
        </div>
    <?endif;?>
</div>


<form method="post">
    <div id="main" class="col-sm-12" >
        <h3>Вы ответили на все вопросы в этой теме.</h3>
    </div>
<!--    <div class="col-sm-8 row mx-auto justify-content-center" >-->
<!--        <input id='submit' class="btn m-2 col-5 col-sm-5 btn-outline-primary" type='submit' value='Ok'>-->
<!--    </div>-->
</form>
<div class="col-sm-7" id="statistic">
    <p>
        Отвечено: <span class="statChoice"><?=$stat['choice']?></span>
        из <span class="statAll"><?=$stat['all']?></span>
        (<span class="statRatioC"><?=$stat['ratioC']?></span>%)
    </p>
    <p>
        Верно: <span class="statRight"><?=$stat['right']?></span>
        из <span class="statChoice"><?=$stat['choice']?></span>
        (<span class="statRatioR"><?=$stat['ratioR']?></span> %)
    </p>
</div>
<div class="progress">
    <div class="progress-bar stat bg-info" role="progressbar" style="width: <?=$stat['ratioC']?>%" aria-valuenow="<?=$stat['ratioC']?>" aria-valuemin="0" aria-valuemax="100"></div>
</div>