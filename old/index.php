<?php
session_start();
?>
<!DOCTYPE HTML>
<html lang="ru">
<head>
		<title>Тестирование Главная</title>
		<meta charset="utf-8">
		<meta name="description" content="Тесты для фельдшеров Санкт-Петербургского училища повышения квалификации специалистов медицинского профиля">
		<meta name="keywords" content="Тесты, фельдшер, санэпидрежим, ЦПО СМП, сертификация">		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link href="style.css?7" type="text/css"  rel="stylesheet" />
		<link href="style<? if($_SESSION['bg']=="black") {echo "_black"; } else {echo "_norm"; }?>.css?7<?//echo time();?>" type="text/css"  rel="stylesheet" />
    </head>
<body>
<!-- Yandex.Metrika counter --> <script> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter44481022 = new Ya.Metrika({ id:44481022, clickmap:true, trackLinks:true, accurateTrackBounce:true, trackHash:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/44481022" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
    <nav>
    	<a class="<?if (($_GET['t'] == null)){echo "act";} ?>" href=".">Начало</a> 
		<a class="<?if (($_GET['t'] == 'feld')){echo "act";} ?>" 	href="feld">Фельдшеры</a> 
		<a class="<?if (($_GET['t'] == 'ser')){echo "act";} ?>" 	href="ser">СанЭпидрежим</a> 
		<a class="<?if (($_GET['t'] == 'smp')){echo "act";} ?>" 	href="smp">Скорая помощь</a><br>
  </nav>

    	<div class='cntr'><br> &#8593; Выбрать тему теcта &#8593;
    	<br>
		<span itemprop="description">
    	Неофициальный сайт студентов-фельдшеров <br>«Центра последипломного образования специалистов медицинского профиля»</span>
    	<br><b>Скачать учебный материал:</b><br>
    	<a href='ECG.pdf'>Экг под силу каждому</a><br><br>
    	<a href='http://www.cpksmo.ru/vibtes.php'>Сайт Училища с тестами</a><br>
    	 <br><br>
    	Сайт сделан на некомерческой основе.<br>
    	Если сайт Вам принес пользу можете<br>
    	<a href='?donate'>поддержать автора материально</a>
   <?
#########====================================================================    
if (isset($_GET['donate']))    {
	?><div class='cntr'>переведя любую сумму<br>
	<iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/embed/donate.xml?account=410012830900101&quickpay=donate&payment-type-choice=on&mobile-payment-type-choice=on&default-sum=100&targets=%D0%BF%D0%BE%D0%B4%D0%B4%D0%B5%D1%80%D0%B6%D0%B0%D1%82%D1%8C+%D1%81%D0%B0%D0%B9%D1%82+%D1%82%D0%B5%D1%81%D1%82%D0%BE%D0%B2&project-name=&project-site=&button-text=05&successURL=" width="508" height="64"></iframe>
	</div><?	};
########======================================================================    	
?>
<br><br><br> © Николай <br> <a href='mailto:it-nikola@mail.ru'>it-nikola@mail.ru</a>
<br>По вопросам работы этого сайта <br> предложения жду на указанную почту<br>
	</div>
    <pre>	<? print_r($_SESSION);   ?></pre>

</body>
</html>