<!--Вопрос № 3
<br>
ИМПУЛЬС, ВЫШЕДШИЙ ИЗ ЦЕНТРА АВТОМАТИЗМА Ш ПОРЯДКА ВЫГЛЯДИТ КАК:
<ul>
    <li class=" m_otv">
        Положительные зубцы Р, широкие комплексы QRS (&gt;0,12") ЧСС 40;
    </li>
    <li class=" m_otv">
        Отрицательный зубец Р, узкий QRS (&lt; 0,12) ЧСС 40;
    </li>
    <li class="r_otv">
        Широкие комплексы QRS (&gt;0,12)6, зубца Р нет, ЧСС &lt; 40 в 1'
    </li>
</ul>
<form class="cntr" action="post" method="post">
    <input class="cntr" type="submit" value="Понятно!" style="padding: 10px 100px;">
</form>
Вопросы: 1 из 3 (33%)
<br>
Верно: 0, всего: 1 (0%)
<br-->
<pre><?print_r($_SESSION)?></pre>
<h1>Вопросы по теме <?=$theme?></h1>
<form action="" id="main" method="post">
    <h2>Вопрос № <?=$num?></h2>
    <h3><?=$quest?></h3>
    <ul>
        <? foreach($answers as $answer): ?>
            <li class="<?
            if($answer['right']) echo "r_otv";
            if($answer['check'] === true) echo " m_otv";
            ?>">
                <p><?=$answer['text']?></p>
            </li>
        <? endforeach; ?>
    </ul>
    <div class='cntr'>
        <input type='submit' value='Ok' style="padding: 10px 100px;">
    </div>
</form>