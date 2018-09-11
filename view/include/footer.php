</div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm needs-validation" role="document">
        <form method="POST" autocomplete="off" id="auth" novalidate class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Представтесь</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class=" modal-body justify-content-center">
                <div class="mb-1">
                    <input id="loginModalLogin" class="form-control" required pattern="[\w\d]{3,20}" type="text" name="login" placeholder="логин или email">
                    <div class="invalid-feedback">
                        необходимо ввести логин или почту.
                    </div>
                </div>
                <div class="mb-1">
                    <input class="form-control" required type="password" name="pass" placeholder="пароль">
                    <div class="invalid-feedback">
                        необходимо ввести пароль.
                    </div>
                </div>
                <div class="mb-2">
                    <input class="form-input h2" type="checkbox" id="saveThis" name="saveThis">
                    <label class="form-label" for="saveThis">Запомнить меня</label>
                </div>
                <div class="mb-2 text-center">
                    <button class="btn btn-primary">Войти</button>
                </div>
            </div>
            <div class="modal-footer">
                <a class="link ml-auto" href="#">Регистрация</a>
                <a class="link mr-auto" href="#">Восстановить пароль</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>