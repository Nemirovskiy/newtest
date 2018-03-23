<div class='cntr'><br>
    <form action="post" method="post">
        <input class="btn m-2 col-sm-2 btn-outline-warning btn-sm" type="submit" name="reset" value="Сброс">
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