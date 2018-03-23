<pre><?//print_r($_SESSION)?></pre>
<h1>Вопросы по теме <?=$theme?></h1>
<div class="message">
    <?=$message?>
</div>
<form action="" id="main" method="post">
    <h2>Вопрос № <?=$number?></h2>
    <h3><?=$quest?></h3>
    <ul>
        <? foreach($answers as $answer): ?>
            <li class="<?
            if($answer['right']) echo "r_otv";
            if($answer['check'] === true) echo " m_otv";
            else echo " otv";
            ?>">
                <p><?=$answer['text']?></p>
            </li>
        <? endforeach; ?>
    </ul>
    <div class="col-sm-8 row mx-auto justify-content-center" >
        <input class="btn m-2 col-sm-5 btn-outline-primary" type='submit' value='Ok'>
    </div>
</form>