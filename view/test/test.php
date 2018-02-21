<h1>Вопросы по теме <?=$theme?></h1>
<form action="" id="main" method="post">
    <h2>Вопрос № <?=$num?></h2>
    <h3><?=$quest?></h3>
    <ul>
    	<? foreach($answers as $answer): ?>
    	<li>
    		<label>
	    		<input class="inpt"
	    			name="<?=$code?>[]"
	    			type='checkbox'
	    			value="<?=$answer['order']?>">
	    		<p><?=$answer['text']?></p>
    		</label>
		</li>
		<? endforeach; ?>
	</ul>
	<div class='cntr'>
    	<input id='submit' disabled type='submit' value='Ok' style="padding: 10px 100px;">
    </div>
</form>
<div class='cntr'><br>
    <form action="post" method="post">
        <input type="submit" name="reset" value="Сброс">
    </form>
    <br>
    <form action="post"  method="post">
        <ul class='sett'>
            <li>Темная тема<br>
                <label>
                    <input type="submit" name="bg" value="black" style="display: none;">
                    <span class='btn_rdn'>
				  <span class="noactive"></span></span>
                </label>
            </li>
            <li>Ответы cлучайно<br>
                <label>
                    <input type="submit" name="rnd" value="no" style="display: none;">
                    <span class='btn_rdn'>
				  <span class="noactive"></span></span>
                </label>
            </li>
            <li>Вопросы бесконечно<br>
                <label>
                    <input type="submit" name="infinity" value="yes" style="display: none;">
                    <span class='btn_rdn'>
				  <span class="active"></span></span>
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