<h4>Вопрос № <?=$num?></h4>
<b><?=$quest?></b>
<ul>
    <? foreach($answers as $answer): ?>
    <li class="<?if($answer['right']) echo "r_otv"?>" >
        <p><?=$answer['text']?></p>
    </li>
    <? endforeach; ?>
</ul>
