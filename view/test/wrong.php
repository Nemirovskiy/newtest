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
    <ul class="col-sm-10 mx-auto card my-3 p-3 bg-light answerList">
        <? foreach($answers as $answer): ?>
            <li class="list-unstyled ml-4 <?
            if($answer['check'] === true) echo " answerCheck";
            if($answer['right']) echo " text-success answerRight";
            ?>">
                <?=$answer['text']?>
            </li>
        <? endforeach; ?>
    </ul>

    <div class="col-sm-8 row mx-auto justify-content-center" >
        <button id='submit' class="btn m-2 col-5 col-sm-5 btn-danger" type='submit'>Далее</button>
    </div>
</form>
<div class="col-sm-7">
    <p>
        Отвечено: <?=$stat['choice']?> из <?=$stat['all']?> (<?=$stat['ratioC']?>%)
    </p>
    <p>
        Верно: <?=$stat['right']?> из <?=$stat['choice']?> (<?=$stat['ratioR']?>%)
    </p>
</div>
<div class="progress">
    <div class="progress-bar stat bg-info" role="progressbar" style="width: <?=$stat['ratioC']?>%" aria-valuenow="<?=$stat['ratioC']?>" aria-valuemin="0" aria-valuemax="100"></div>
</div>