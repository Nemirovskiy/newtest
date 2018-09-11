<div class='cntr'>
    <h2>Вход только по пропуску</h2>
    <form method="POST">
        <input autofocus type="text" name="login" placeholder="login/email">
        <br>
        <input type="password" autocomplete="off" name="pass" placeholder="password">
        <br>
        <button>Войти</button>
        <br>
        <a href="#">Регистрация</a>
    </form>
    <form method="POST">
        <input autofocus type="text" name="login" placeholder="login/email">
        <br>
        <input type="password" autocomplete="off" name="passNew" placeholder="password">
        <input type="password" autocomplete="off" name="passRet" placeholder="password">
        <br>
        <button>Регистрация</button>

    </form>

    <form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml">
        <input type="hidden" name="receiver" value="410012830900101">
        <input type="hidden" name="formcomment" value="Сайт тестов">
<!--        <input type="hidden" name="short-dest" value="Тесты фельдшеров">-->
<!--        <input type="hidden" name="label" value="9999">-->
        <input type="hidden" name="quickpay-form" value="donate">
        <input type="hidden" name="targets" value="поддержать сайт">
        <input type="number" name="sum" required value="100" data-type="number">
        <input type="hidden" name="comment" value="Хотелось бы получить дистанционное управление.">
        <input type="hidden" name="need-fio" value="false">
        <input type="hidden" name="need-email" value="false">
        <input type="hidden" name="need-phone" value="false">
        <input type="hidden" name="need-address" value="false">
        <label><input type="radio" name="paymentType" value="PC">Яндекс.Деньгами</label>
        <label><input type="radio" name="paymentType" value="AC">Банковской картой</label>
        <label><input type="radio" name="paymentType" value="MC">Со счета телефона</label>
        <input type="submit" value="Перевести"></form>
</div>