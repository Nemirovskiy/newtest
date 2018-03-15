<h4><span>№ <?=$num?> </span><?=$quest?></h4>
<ul>
    <? foreach($answers as $answer): ?>
    <li class="<?if($answer['right']) echo "r_otv"?>" >
        <?=$answer['text']?>
    </li>
    <? endforeach; ?>
</ul>
