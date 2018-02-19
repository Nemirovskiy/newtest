<?php
session_start();
## если пустой пост - вернем на ту же страницу, если перешел не по сслыке (на прямую) - на главную
if (!isset($_SERVER['HTTP_REFERER'])) {header("Location: .");die;}
if (empty($_POST)) {header("Location: ".$_SERVER['HTTP_REFERER']);die;}


//устанавливаем значения по умолчанию
if (($_POST['reset'] !=null) or !isset($_SESSION['ball'.$_POST['t']])) {$_SESSION['ball'.$_POST['t']]=0;};
if (($_POST['reset'] !=null) or !isset($_SESSION['qwest'])) {$_SESSION['qwest']=0;};
if (($_POST['reset'] !=null) or !isset($_SESSION['err'.$_POST['t']])) {$_SESSION['err'.$_POST['t']]=0;};
if (($_POST['reset'] !=null) or !isset($_SESSION['rez'.$_POST['t']])) {$_SESSION['rez'.$_POST['t']]=0;};
if (($_POST['reset'] !=null) or !isset($_SESSION['otvet'])) {$_SESSION['otvet']=0;};
if (($_POST['reset'] !=null) or !isset($_SESSION['logsmp'])) {$_SESSION['logsmp']=null;};
if (($_POST['reset'] !=null) or !isset($_SESSION['logser'])) {$_SESSION['logser']=null;};
if (($_POST['reset'] !=null) or !isset($_SESSION['logfeld'])) {$_SESSION['logfeld']=null;};
if (($_POST['reset'] !=null) or !isset($_SESSION['reqwest'])) {$_SESSION['reqwest']=null;};//ответная часть 
if (($_POST['reset'] !=null) or !isset($_SESSION['r_qw'])) {$_SESSION['r_qw']=null;};//правильность ответа 
if (($_POST['reset'] !=null) or !isset($_SESSION['mass_'.$_POST['t']])) {$_SESSION['mass_'.$_POST['t']]=null;};//правильность ответа  
if (!isset($_SESSION['rnd'])) {$_SESSION['rnd']='no';};//случайный ответ
if (!isset($_SESSION['bg'])) {$_SESSION['bg']='no';};//темная тема 
if (!isset($_SESSION['infinity'])) {$_SESSION['infinity']='no';};//темная тема 


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
##
	if ($_POST['bg'] == 'black') {$_SESSION['bg']='black';}
	elseif ($_POST['bg'] == 'no') {$_SESSION['bg']='no';}
##
	if ($_POST['infinity'] == 'yes') {$_SESSION['infinity']='yes';}
	elseif ($_POST['infinity'] == 'no') {$_SESSION['infinity']='no';}
##
	if (isset($_POST['infotab'])) {$_SESSION['infotab']='yes';}
################################################
## разбор полученных ответов
## формировани строки ответов для проверки
for ($x=1, $z=1;$x<11;$x++) {
		$y= 'otvet' . $x;
		if(isset($_POST[$y])) { if ($z>1){$p_otvet = $p_otvet. ',';}
			$p_otvet = $p_otvet. $_POST[$y]; 
			$z++;
		};
	};
////////////////////////////////////////////////////////////////////////////////////////////
## проверка правильности ответов
 if ((isset($p_otvet)) && (($_SESSION['otvet']) == $p_otvet)) 
 	{
 		$_SESSION['ball'.$_POST['t']]++;//запись в сессию количество правильно отвеченных 
 		$_SESSION['r_qw'] = 'ok'; 
 		$_SESSION['reqwest'] .= "<h1>Верно!</h1>";
 	}
//запись лога в сессию
 if (($p_otvet != null ) && isset($p_otvet)) {
 	$log = $_SESSION['log'.$_POST['t']];
 	$log[$_SESSION['rez'.$_POST['t']]] = $_SESSION['qwest'];
 	$_SESSION['log'.$_POST['t']] = $log;	//запись в сессию лога
 	$_SESSION['rez'.$_POST['t']]++;			//запись в сессию общее количество ответов
 };
 
    $test = file($_POST['t'].'-test.txt');
    $test_otv = file($_POST['t'].'-test-otv.txt');
    
    $i=0;
    foreach($test as $v){
    	if (preg_match("#(\#)([0-9]*)(\.)(.*)#", $v, $s)) 
    		{unset($txt);$i++;$ii=1; $txt[0] = $s[4] ; $mass[$i] =$txt ;}
    	if (preg_match("#([а-я])(\))(.*)()#", $v, $s)) 
    		{$txt[$ii] = $s[3] ; $mass[$i] = $txt; $ii++;}
    }
    
##############################################################################################################
##
##		Формируем список правильных ответов c записью  в переменную сессии
##
$old_test = $mass[$_SESSION['qwest']];
if (isset($p_otvet) && ($p_otvet != null ) && (($_SESSION['otvet']) != $p_otvet)) {
	$_SESSION['r_qw'] = 'err';
  $_SESSION['reqwest'] .=  " Вопрос № ".$_SESSION['qwest'].'<br>';
  $_SESSION['reqwest'] .= $old_test[0];
  $_SESSION['reqwest'] .= "<ul>";
  $_SESSION['err'.$_POST['t']]= $_SESSION['err'.$_POST['t']]." ".$_SESSION['qwest'];
    $y= count($old_test);
    for ($x=1; $x<count($old_test); $x++)
    	{
    	$_SESSION['reqwest'] .='<li class="';
    		if(strpbrk($_SESSION['otvet'], $x)){
    			$_SESSION['reqwest'] .= "r_otv";};
    		if($x == $_POST['otvet'.$x]){
    			$_SESSION['reqwest'] .= " m_otv";};
    		$_SESSION['reqwest'] .= '">'. $old_test[$x] .'</li>';
    	};
    $_SESSION['reqwest'] .= '</ul>'; 
	};
header("Location: ".$_SERVER['HTTP_REFERER']); /* Redirect browser */
exit;
      ?>
      