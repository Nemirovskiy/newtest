<? if(!empty($message)): ?>
    <h4 style="color: blue;"><?=$message?></h4>
<?endif;?>
<? if(!empty($errors)): ?>
    <div class="modal fade" id="errors" tabindex="-1"
         role="dialog" aria-labelledby="errors" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Ошибка</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body alert alert-danger" role="alert">
                    <?=$errors?>
                </div>
            </div>
        </div>
    </div>
    <h4 style="color: red;"></h4>
    <script>
        $('#errors').modal();
    </script>
<?endif;?>
<div class='cntr'>
<h1>Администратор</h1>
<p>Это кабинет администратора</p>
</div>