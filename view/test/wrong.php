<pre><?//print_r($_SESSION)?></pre>
<h1>Вопросы по теме <?=$theme?></h1>
<div id="message" class="testMessage">
    <?if(!empty($message['test'])):?>
        <div class="alert alert-primary" role="alert" >
            <?=$message['test']?>
        </div>
    <?endif;?>
</div>
<form action="" id="main" method="post">
    <h2>Вопрос № <?=$number?></h2>
    <h3><?=$quest?></h3>
    <ul class="answerList">
        <? foreach($answers as $answer): ?>
            <li class="<?
            if($answer['check'] === true) echo " answerCheck";
            if($answer['right']) echo " text-success answerRight";
            //else echo " otv";
            ?>">
                <p><?=$answer['text']?></p>
            </li>
        <? endforeach; ?>
    </ul>
    <div class="col-sm-8 row mx-auto justify-content-center" >
        <input class="btn m-2 col-sm-5 btn-outline-primary" type='submit' value='Ok'>
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
    <div class="progress-bar
    <?if($stat['ratioR'] > 75):?>
    bg-info
    <?else:?>
    bg-danger
    <?endif;?>" role="progressbar" style="width: <?=$stat['ratioC']?>%" aria-valuenow="<?=$stat['ratioC']?>" aria-valuemin="0" aria-valuemax="100"></div>
</div>