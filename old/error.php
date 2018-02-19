<?php
session_start();
?>

<html>
<head>
		<title>Ошибка!</title>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="content-language" content="ru" />
		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link href="style.css?5<?echo time();?>" type="text/css"  rel="stylesheet" />		
		<link href="style<? if($_SESSION['bg']=="black") {echo "_black"; } else {echo "_norm"; }?>.css?6<?echo time();?>" type="text/css"  rel="stylesheet" />
    </head>
<body>

<!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter44481022 = new Ya.Metrika({ id:44481022, clickmap:true, trackLinks:true, accurateTrackBounce:true, trackHash:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/44481022" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
    <nav>
    	<a href=".">Начало</a> 
		<a href="feld">Фельдшеры</a> 
		<a href="ser">СанЭпидрежим</a> 
		<a href="smp">Скорая помощь</a><br>
  </nav>
  
  <div class='cntr'><br><br>
  <h1>Возникла ошибка!</h1><br><br>Не переживайте, попробуйте перейти по ссылки в меню
  </div>
</body>
</html>