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