<h1>Вопросы по теме <?=$theme['text']?></h1>
<form action="post" id="main" method="post">
    <h2>Вопрос № <?=$num?></h2>
    <h3><?=$quest?></h3>
    <ul>
    	<?foreach($answers as $answer):?>
    	<li>
    		<label>
	    		<input class="inpt"
	    			name="answer_<?=$theme['name']?>_<?=$answer['order']?>"
	    			type='checkbox'
	    			value="<?=$answer['value']?>">
	    		<p><?=$answer['text']?></p>
    		</label>
		</li>
		<?endforeach;?>
	</ul>
	<div class='cntr'>
		<input type="hidden" name="t" value="<? echo $_GET['t'];?>">
    	<input id='submit' disabled type='submit' value='Ok' style="padding: 10px 100px;">
    </div>
</form> 
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