<?php
$time= microtime();
session_start();
$arr_test=[1=>'ser','feld','smp']; //массив ссылок тестов
$arr_name=[1=>'СанЭпидРежим','Фельдшеры','Скорая помощь'];//массив названий тестов
$arr=['id'=>$arr_test,'name'=>$arr_name];
## если неверный адрес страницы - показываем главную
if (!in_array($_GET['t'],$arr_test)) {header("Location: .");die;}
function set_title($get)
		{
			global $arr;
			switch($get){
				case $arr['id'][1]:  echo $arr['name'][1]; break;
				case $arr['id'][2]:  echo $arr['name'][2]; break;
				case $arr['id'][3]:  echo $arr['name'][3]; break;
			}
		}
?>
<!DOCTYPE HTML>
<html lang="ru">
<head>
		<title>Тестирование <? set_title($_GET['t']);?></title>
		<meta charset="utf-8">
		<meta name="description" content="Тесты для фельдшеров Санкт-Петербургского училища повышения квалификации специалистов медицинского профиля">
		<meta name="keywords" content="Тесты, фельдшер, санэпидрежим, ЦПО СМП, сертификация">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link href="style.css?7<?//echo time();?>" type="text/css"  rel="stylesheet" />		
		<link href="style<? if($_SESSION['bg']=="black") {echo "_black"; } else {echo "_norm"; }?>.css?7<?//echo time();?>" type="text/css"  rel="stylesheet" />
    </head>
<body><?print_r($_GET);?>
<!-- Yandex.Metrika counter --> <script> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter44481022 = new Ya.Metrika({ id:44481022, clickmap:true, trackLinks:true, accurateTrackBounce:true, trackHash:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/44481022" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
    <nav>
    	<a class="<?if (($_GET['t'] == null)){echo "act";} ?>" href=".">Начало</a> 
		<a class="<?if (($_GET['t'] == 'feld')){echo "act";} ?>" 	href="feld">Фельдшеры</a> 
		<a class="<?if (($_GET['t'] == 'ser')){echo "act";} ?>" 	href="ser">СанЭпидрежим</a> 
		<a class="<?if (($_GET['t'] == 'smp')){echo "act";} ?>" 	href="smp">Скорая помощь</a><br>
  </nav>
<?
//устанавливаем значения по умолчанию
if (!isset($_SESSION['rez'.$_GET['t']])) {$_SESSION['rez'.$_GET['t']]=0;};
if (!isset($_SESSION['ball'.$_GET['t']])) {$_SESSION['ball'.$_GET['t']]=0;};
if (!isset($_SESSION['qwest'])) {$_SESSION['qwest']=0;};
if (!isset($_SESSION['err'.$_GET['t']])) {$_SESSION['err'.$_GET['t']]=0;};
if (!isset($_SESSION['otvet'])) {$_SESSION['otvet']=0;};
if (!isset($_SESSION['logsmp'])) {$_SESSION['logsmp']=null;};
if (!isset($_SESSION['logser'])) {$_SESSION['logser']=null;};
if (!isset($_SESSION['logfeld'])) {$_SESSION['logfeld']=null;};
if (!isset($_SESSION['rnd'])) {$_SESSION['rnd']='no';};
if (!isset($_SESSION['bg'])) {$_SESSION['bg']='no';};//темная тема 
if (!isset($_SESSION['infinity'])) {$_SESSION['infinity']='no';};//темная тема 
if (!isset($_SESSION['infotab'])) {$_SESSION['infotab']='no';};//информация 

///////////////////////////////////////////////////////////////////
if ($_SESSION['infotab'] == 'no'):
?>
<div id='in fo'>
<p class='cntr'>
<b> Справка.</b>
<ul>
	<li>Выбрать тему теста в верхнем меню </li>
	<li>Вопросы идут в случайном порядке </li>
	<li>Внизу переключатели режимов работы </li>
	<li>Вопросы не повторяются в теме, если включено [Вопросы&nbsp;бесконечно] вопросы бесконечно повторяются в случайном порядке</li>	
	<li>Ответы идут по порядку, если включено [Ответы&nbsp;случайно] ответы перемешиваются</li>
	<li>Время не ограничено</li>
	<li>Вся статистика по ответам и настройки режимов хранются только в рамках текущего сеанса, статистика сбрасывается для текущей темы кнопкой [Сброс]</li>	
	<li>Эта справка показывается одноктратно за сеанс</li>	
</ul>

      <form class='cntr' action="post" method="post">
      	<input type="submit" name="infotab" value="Закрыть" style="padding: 10px 100px;">
      </form></p><br>
</div>
<?
endif;
//Вывод информации из сессии
echo $_SESSION['reqwest'] ;
 //////////// ЧИТАЕМ ответы и вопросы
    $test_otv = file($_GET['t'].'-test-otv.txt');
    if (empty($_SESSION['mass_'.$_GET['t']])) ///////записываем вопросы в сессию
    	{
    		$test = file($_GET['t'].'-test.txt');
	    	$i=0;
		    foreach($test as $v){
		    	if (preg_match("#(\#)([0-9]*)(\.)(.*)#", $v, $s)) //чтение вопроса
		    		{unset($txt);$i++;$ii=1; $txt[0] = $s[4] ; $mass[$i] =$txt ;}
		    	if (preg_match("#([а-я])(\))(.*)()#", $v, $s)) //чтение вариантов ответа
		    		{$txt[$ii] = $s[3] ; $mass[$i] = $txt; $ii++;}
		    }
		    $_SESSION['mass_'.$_GET['t']] = $mass;
    	}
		else {$mass = $_SESSION['mass_'.$_GET['t']];}
############################################################################################################## 
    //подсчет статистики по отвеченным вопросам
	if ((isset($_SESSION['rez'.$_GET['t']])) && ($_SESSION['rez'.$_GET['t']] != null)) 
			{$ratio_all = floor($_SESSION['rez'.$_GET['t']] / count($mass) * 100);} //отвечено верно
		else {$ratio_all = 0 ;};
	if ((isset($_SESSION['ball'.$_GET['t']])) && ($_SESSION['ball'.$_GET['t']] != null)) 
 			{$ratio_ball = floor($_SESSION['ball'.$_GET['t']] / $_SESSION['rez'.$_GET['t']] * 100);} //отвечено всего
		else {$ratio_ball = 0 ;};		
    if (count($_SESSION['log'.$_GET['t']]) >=  count($mass) && ($_SESSION['r_qw'] != 'err') && ($_SESSION['infinity']=='no') )
	  {echo "<div class='cntr'><h3>Вы ответили на все вопросы в этой теме.</h3>";	  	
	  	if ($ratio_ball >= 90) {echo "<h2>Вы сдали!</h2>";}
	  	else {echo "<h2>Вы не сдали!</h2>";}
	  	//echo "Неверные ответы:<br>".$_SESSION['err'.$_GET['t']];
	  	echo "<br>".$ratio_ball."%</div>";
	  }	  
	  elseif ($_SESSION['r_qw'] == 'err') {
	  	echo '<form class="cntr" action="post" class="myform" method="post"><input class="cntr" type="submit" value="Понятно!" style="padding: 10px 100px;"></form>';
	  }
	  	else {
	  	##
	  	##########################################################
	  	##		Генерируем случайной число для вопроса
	  	##	
    if ($_SESSION['infinity'] == 'no'){//если не бесконечно, то проверяем наличие вопроса в сессии
	do {$num = rand(1, count($mass));} while (in_array($num, $_SESSION['log'.$_GET['t']])  && ($_SESSION['rez'.$_GET['t']] <= count($mass)));}
	else {$num = rand(1, count($mass));}// если бесконечность включена - не проверяем наличие вопроса в сессии	
	$one_test = $mass[$num];//формирование массива текущего вопроса
	$_SESSION['qwest'] = $num; //запись в сессию номер вопроса
##
####################################################
## Формируем список вопросов
?> 
    <form action="post" id="main" method="post">
    <?
	echo "<br> Вопрос № ".$num.'<br>'; 
    echo $one_test[0];
    echo"<ul>";
    //начало выборки для случайного ответа
    $y= count($one_test);
	$ar_r = array(0);
	$i = 1;
	do 	{
		 $rnd = rand(1,$y-1);
		 if (!in_array($rnd,$ar_r)) {$ar_r[]=$rnd;$i++;};		
		}
	while ($i < $y);
	///конец случайной выборки для случайного ответа
	//цикл списка ответов
    for ($x=1; $x<count($one_test); $x++)
    	{?>
    		<li><? //echo $y - 1; echo $x;echo $ar_r[$x];
			?>
	    		<label>
		    		<input  class='inpt' name="otvet<?if ($_SESSION['rnd'] == 'yes') 
		    		{echo $ar_r[$x];}else {echo $x;} ?>" type='checkbox' value="<?if ($_SESSION['rnd'] == 'yes') {echo $ar_r[$x];}else {echo $x;} ?>">
		    		<p><?if ($_SESSION['rnd'] == 'yes') {echo $one_test[$ar_r[$x]];}else {echo $one_test[$x];} ?></p>
	    		</label>
    		</li>
    		<?
    	};
    ?></ul>
    <div class='cntr'><input type="hidden" name="t" value="<? echo $_GET['t'];?>">
    <input id='submit' disabled type='submit' value='Ok' style="padding: 10px 100px;"></div></form> 

      <?  
#############################################################################################
##		формируем правильный ответ для проверки 
##
    $ser = preg_split("(\w+)", $test_otv[$num-1]);
    $search  = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'И', 'З', 'а', 'б', 'в', 'г', 'д', 'е', 'ж', 'з', 'и');
	$replace = array('1', '2', '3', '4', '5','6', '7', '8', '9', '1', '2', '3', '4', '5', '6', '7', '8', '9');
	$strr = strtolower($ser[1]);
	$_SESSION['otvet'] = str_replace($search, $replace, trim($strr));
	  }//конец если вопросов больше нет	      
			if ($_SESSION['infinity'] != 'yes') {echo "Вопросы: ". $_SESSION['rez'.$_GET['t']]. " из ". count($mass) . " (".$ratio_all."%)<br>";}
    
      echo "Верно: ".$_SESSION['ball'.$_GET['t']].", всего: " . $_SESSION['rez'.$_GET['t']] . " (" 
      				.$ratio_ball."%)<br>";
        ?><div class='cntr'><br>
      <form action="post" method="post">
      	<input type="hidden" name="t" value="<? echo $_GET['t'];?>">
      	<input type="submit" name="reset" value="Сброс">
      </form>
	  <br>	  
  	  <form action="post"  method="post">	 
		<ul class='sett'>	  
		  <li>Темная тема<br>
		  <label>
			  <input onclick="yaCounter44481022.reachGoal('bg'); return true;" type="submit" name="bg" value="<? if ($_SESSION['bg'] == 'black') {echo 'no';} else {echo 'black';} ?>" style="display: none;">
				  <span class='btn_rdn'>
				  <span class="<?if ($_SESSION['bg'] == 'no') {echo 'no';}?>active"></span></span>
		  </label>
		  </li>
		  <li>Ответы cлучайно<br>
		  <label>
			  <input onclick="yaCounter44481022.reachGoal('rnd'); return true;" type="submit" name="rnd" value="<? if ($_SESSION['rnd'] == 'yes') {echo 'no';} else {echo 'yes';} ?>" style="display: none;">
				  <span class='btn_rdn'>
				  <span class="<?if ($_SESSION['rnd'] == 'no') {echo 'no';}?>active"></span></span>
		  </label>
		  </li>
		  <li>Вопросы бесконечно<br>
		  <label>
			  <input onclick="yaCounter44481022.reachGoal('inf'); return true;" type="submit" name="infinity" value="<? if ($_SESSION['infinity'] == 'yes') {echo 'no';} else {echo 'yes';} ?>" style="display: none;">
				  <span class='btn_rdn'>
				  <span class="<?if ($_SESSION['infinity'] == 'no') {echo 'no';}?>active"></span></span>
		  </label>
		  </li>
		  </ul>
	  </form>	  
      <br><br><br><a   href='<? echo $_GET['t'].'-test.txt';?>'>[Скачать тест]</a>
   <a   href='<? echo $_GET['t'].'-test-otv.txt';?>'>[Скачать ответы]</a><br>
    	<br>
    	Сайт сделан на некомерческой основе.<br>
    	Если сайт Вам принес пользу можете<br>
    	<a href='.?donate'>поддержать автора материально</a>
		<br>
	</div>
    	<?
    	//Обнуление сообщений от обработчика
    	$_SESSION['r_qw']=null;
    	$_SESSION['reqwest'] = null;?>
<div class='info_bg'></div></body>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script>
$(function() {
  $('#submit').prop('disabled', true);
  $('.inpt').change(function() {
    if($('.inpt:checked').length > 0) {            
      $('#submit').prop('disabled', false);
    }
	else
	{$('#submit').prop('disabled', true);}
  });
});
</script>
</html>