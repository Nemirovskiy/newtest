<pre><?//print_r($tests)?></pre>
<!--foreach ($tests as $test){-->
<!--//$_SESSION["curent"] = $test;-->
<!--$theme = $test["theme"];-->
<!--//$code = $testTheme;-->
<!--$num = $test["number"];-->
<!--$quest = $test["quest"];-->
<!--$answers = $test["answers"];-->
<!--}-->
<h1>Вопросы по теме <?=$tests[1]["theme"]?></h1>
<ul>
    <?foreach ($tests as $test):?>
        <li><b>№ <?=$test["number"]?> </b><?=$test["quest"]?></li>
        <? foreach($test["answers"] as $answer): ?>
            <li class="<?
            if($answer['right']) echo "r_otv";
            if($answer['check'] === true) echo " m_otv";
            ?>">
                <?=$answer['text']?>
            </li>
        <? endforeach; ?>
    <? endforeach; ?>
</ul>
