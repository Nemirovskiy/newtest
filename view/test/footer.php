
<div class='cntr'><br>
    <form method="post">
        <button id="reset" class="btn m-2 col-sm-2 col-3 btn-secondary"  name="reset" value="<?=$code?>">Сброс</button>
    </form>
    <div class="form-group row">
        <label for="exampleSelect1" class="col">Количество вопросов</label>
        <select name="limit" class="form-control col" id="exampleSelect1">
            <option value="all">Все</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="no">Бесконечно</option>
        </select>
    </div>
    <br>
</div>