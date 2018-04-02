<h1>Вопросы по теме <?=$theme?></h1>
<div id="messageTest" class="testMessage col-sm-5">
    <?if(!empty($message['test'])):?>
        <div class="alert alert-primary" role="alert" >
            <?=$message['test']?>
        </div>
    <?endif;?>
</div>


<form action="" method="post">
    <div id="main" class="col-sm-12" >
        <h2>Вопрос № <span id="number"><?=$number?></span> </h2>
        <h3 id="quest"><?=$quest?></h3>
        <div class="col-sm">
            <? foreach($answers as $answer): ?>
                <label class="row align-items-center">
                    <input class="inpt "
                           name="<?=$code?>[]"
                           type='checkbox'
                           value="<?=$answer['order']?>">
                    <p class="col"><?=$answer['text']?></p>
                </label>
            <? endforeach; ?>
        </div>
    </div>
    <div class="col-sm-8 row mx-auto justify-content-center" >
        <input id='submit' class="btn m-2 col-5 col-sm-5 btn-outline-primary" type='submit' value='Ok'>
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