<?php
session_start();
//устанавливаем значения по умолчанию
if (($_POST['reset'] !=null) or !isset($_SESSION['ball'.$_POST['t']])) {$_SESSION['ball'.$_POST['t']]=0;};
if (($_POST['reset'] !=null) or !isset($_SESSION['qwest'])) {$_SESSION['qwest']=0;};
if (($_POST['reset'] !=null) or !isset($_SESSION['err'.$_POST['t']])) {$_SESSION['err'.$_POST['t']]=0;};
if (($_POST['reset'] !=null) or !isset($_SESSION['rez'.$_POST['t']])) {$_SESSION['rez'.$_POST['t']]=0;};
if (($_POST['reset'] !=null) or !isset($_SESSION['otvet'])) {$_SESSION['otvet']=0;};
if (($_POST['reset'] !=null) or !isset($_SESSION['logsmp'])) {$_SESSION['logsmp']=null;};
if (($_POST['reset'] !=null) or !isset($_SESSION['logser'])) {$_SESSION['logser']=null;};
if (($_POST['reset'] !=null) or !isset($_SESSION['logfeld'])) {$_SESSION['logfeld']=null;};
if (($_POST['reset'] !=null) or !isset($_SESSION['rnd'])) {$_SESSION['rnd']='no';};
if (($_POST['reset'] !=null) or !isset($_SESSION['reqwest'])) {$_SESSION['reqwest']=null;};//ответная часть 
if (($_POST['reset'] !=null) or !isset($_SESSION['r_qw'])) {$_SESSION['r_qw']=null;};//правильность ответа 
if (($_POST['reset'] !=null) or !isset($_SESSION['mass_'.$_POST['t']])) {$_SESSION['mass_'.$_POST['t']]=null;};//правильность ответа  
if (($_POST['reset'] !=null) or !isset($_SESSION['bg'])) {$_SESSION['bg']='no';};

############################################
if (($_POST['reset']) != null ) {
	header("Location: ".$_SERVER['HTTP_REFERER']); /* Redirect browser */
	exit;
	};
if (($_POST['first']) != null ) {
	$_SESSION['r_qw'] = 'ok';
	header("Location: ".$_SERVER['HTTP_REFERER']); /* Redirect browser */
	exit;
	};
 	
############################################
##
##	Переключатель при нажатии и передачи постом 
##
	if ($_POST['rnd'] == 'yes') {$_SESSION['rnd']='yes';}
	elseif ($_POST['rnd'] == 'no') {$_SESSION['rnd']='no';}
	
	/////////////////////////////////////////////////////////
	if ($_POST['bg'] == 'black') {$_SESSION['bg']='black';}
	elseif ($_POST['bg'] == 'no') {$_SESSION['bg']='no';}
##
################################################
## 
      ?>
<!DOCTYPE HTML>
<html>
<head>
		<title>Тестирование Главная</title>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="content-language" content="ru" />
		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link href="style.css?<?echo time();?>" type="text/css"  rel="stylesheet" />
    </head>
<body>
<?    
   if($_SESSION['bg']=="black") 
   {echo "<style>* {background: #000; color: #fff;} input[type='checkbox'] + p { background: #000!important;color: #fff!important;} input[type='checkbox']:checked + p {background: #ccc!important;}</style>"; };
 ?>
<!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter44481022 = new Ya.Metrika({ id:44481022, clickmap:true, trackLinks:true, accurateTrackBounce:true, trackHash:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/44481022" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
    <nav>
    	<a class="<?if (($_GET['t'] == null)){echo "act";} ?>" href=".">Начало</a> 
		<a class="<?if (($_GET['t'] == 'feld')){echo "act";} ?>" 	href="feld">Фельдшеры</a> 
		<a class="<?if (($_GET['t'] == 'ser')){echo "act";} ?>" 	href="ser">СанЭпидрежим</a> 
		<a class="<?if (($_GET['t'] == 'smp')){echo "act";} ?>" 	href="smp">Скорая помощь</a><br>
  </nav>     
    <form action="post" method="post">
      	<input type="hidden" name="t" value="<? echo $_GET['t'];?>">
      	<input type="submit" name="reset" value="Сброс">
      </form>
  	  <form  method="post">	 
		<ul class='sett'>	  
		  <li>Темная тема<br>
		  <label>
			  <input type="submit" name="bg" value="<? if ($_SESSION['bg'] == 'black') {echo 'no';} else {echo 'black';} ?>" style="display: none;">
				  <span class='btn_rdn'>
				  <span class="<?if ($_SESSION['bg'] == 'no') {echo 'no';}?>active"></span></span>
		  </label>
		  </li>
		  <li>Ответы cлучайно<br>
		  <label>
			  <input type="submit" name="rnd" value="<? if ($_SESSION['rnd'] == 'yes') {echo 'no';} else {echo 'yes';} ?>" style="display: none;">
				  <span class='btn_rdn'>
				  <span class="<?if ($_SESSION['rnd'] == 'no') {echo 'no';}?>active"></span></span>
		  </label>
		  </li>
		  <li>Ответы cлучайно<br>
		  <label>
			  <input type="submit" name="rnd" value="<? if ($_SESSION['rnd'] == 'yes') {echo 'no';} else {echo 'yes';} ?>" style="display: none;">
				  <span class='btn_rdn'>
				  <span class="<?if ($_SESSION['rnd'] == 'no') {echo 'no';}?>active"></span></span>
		  </label>
		  </li>
		  </ul>
	  </form>
      
</body>
</html>        
      