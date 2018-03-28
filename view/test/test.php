<h1>Вопросы по теме <?=$theme?></h1>
<div id="message" class="testMessage">
    <?if(!empty($message)):?>
        <div class="alert alert-primary" role="alert" >
            <?=$message?>
        </div>
    <?endif;?>
</div>


<form action="" method="post">
    <div id="main" class="col-sm-12" >
        <h2>Вопрос № <span id="number"><?=$number?></span> </h2>
        <h3 id="quest"><?=$quest?></h3>
        <div class="col-sm">
            <? foreach($answers as $answer): ?>
                <label class="row align-items-center">
                    <input class="inpt "
                           name="<?=$code?>[]"
                           type='checkbox'
                           value="<?=$answer['order']?>">
                    <p class="col"><?=$answer['text']?></p>
                </label>
            <? endforeach; ?>
        </div>
    </div>
    <div class="col-sm-8 row mx-auto justify-content-center" >
        <input id='submit' class="btn m-2 col-5 col-sm-5 btn-outline-primary" disabled type='submit' value='Ok'>
    </div>
</form>
<script>
    $(function () {
        function wrong(e) {
            var x = 0;
            console.log(e);
            $('#message').text(e.message);
            //if(e.wrong === "true"){
                $('[type=\'checkbox\']').each(function() {
                    $(this).prop('disabled', true);
                    console.log(this);
                    if(e.answers[x].right == 1)
                        $(this).next('p').addClass('bg-success text-white');
                    x++;
                })

            //}
        };
        function newTest(e) {
            var x = 0;
            console.log(e);
            $('#message').text('');
            $('#number').text(e.number);
            $('#quest').text(e.quest);
            //if(e.wrong === "true"){
                $('[type=\'checkbox\']').each(function() {
                    $(this).prop('disabled', false);
                    $(this).prop('checked', false);
                    console.log(this);
                    //if(e.answers[x].right == 1)
                    $(this).next('p').removeClass('bg-success text-white');
                    $(this).next('p').text(e.answers[x].text);
                    x++;
                })
            //}
        };

        $('#submit').on('click',function (e) {
            e.preventDefault();
            var x = 0;
            var val = [];
            $('input:checked').not(':disabled').each(function () {
                val.push($(this).val());
            });
            var data = {
                "ajax": "ajax",
                "smp": val,
            }
            console.log(val);
            $.ajax({
                method: 'POST',
                // url: '<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>',
                dataType: 'json',
                data: data,
                success: function (e) {
                    if(e.wrong == 'true'){
                        wrong(e);
                    }else{
                        newTest(e);
                    }
                }
            })
        });
    })
</script>