<div id="messageTest" class="testMessage col-sm-5">
    <?if(!empty($message['test'])):?>
        <div class="alert alert-third" role="alert" >
            <?=$message['test']?>
        </div>
    <?endif;?>
</div>

<form action="" method="post">
    <!--    <div id="main">-->
    <div class="card p-3 bg-light mt-3">
        <h2 class="text-center">Вопрос № <?=$number?> </h2>
        <h3><?=$quest?></h3>
    </div>

    <div class="col-sm-10 mx-auto card my-3 p-3 bg-light">
        <? foreach($answers as $answer): ?>
            <label class="row align-items-center my-0 mx-2">
                <input class="inpt "
                       name="<?=$code?>[]"
                       type='checkbox'
                       value="<?=$answer['order']?>">
                <p class="col p-1 m-0"><?=$answer['text']?></p>
            </label>
        <? endforeach; ?>
    </div>
    <!--    </div>-->
    <div class="col-sm-8 row mx-auto justify-content-center" >
        <button id='submit' class="btn m-2 col-5 col-sm-5 btn-secondary" type='submit'>Далее</button>
    </div>
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