<!DOCTYPE html>
<html lang="ru">
<head>
    <meta name="robots" content="noindex,nofollow">
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <!--    <script src="js/main.js?333"></script>-->
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/style/style.css?9868">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title><?=$title?></title>
</head>
<body>
<div class="container">
    <header>
        <h2>
            <script>
               // document.write(location.search);
            </script>
        </h2>
        <div class="row mt-md-3 justify-content-center">
            <a href="/"><img src="/logo.png" alt="logo" style="max-width: 60px;height: 50px" class="d-none d-md-block "></a>
            <div class="text-secondary text-center col-8 d-none d-md-block ">
                Неофициальный сайт студентов-фельдшеров
                «Центра последипломного образования специалистов медицинского профиля»
            </div>
        </div>

        <noscript>
            <form class="d-none alert text-center alert-warning alert-dismissible fade show" >
                <button type="submit" name="noJavaScript" class="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Ваш браузер работает без JavaScript</strong><br>
                Функционал сайта будет ограничен.
            </form>
        </noscript>
        <?if(!$accept):?>
            <form method="POST" id="accept" class=" col alert alert-warning alert-dismissible fade show" role="alert">
                <h1>Сайт в разработке!</h1>
                <span>
                    Тесты загружены для проверки работы сайта.
                    Могут возникать ошибки. <br>
                    Продолжая пользоваться сайтом, Вы соглашаетесь с этим или покиньте сайт.  <br>

                </span>
                <button type="submit" name="accept" class="btn btn-third ml-auto"  ><!--data-dismiss="alert" aria-label="Close"-->
                    <span aria-hidden="true">Согласен.</span>
                </button>
            </form>
        <?endif;?>
        <div class="navbar-expand-md navbar-dark d-flex flex-column-reverse flex-md-column" role="navigation">
            <nav id="nav" class="collapse navbar-collapse my-2 justify-content-center">
                <div class="col-md d-flex d-lg-block flex-wrap navbar-light">
                    <ul class="list-unstyled col d-md-flex justify-content-center row-md mx-auto">
                        <? foreach($nav as $link):?>
                            <li class="nav-item mx-md-2 mb-2 mb-md-0">
                                <a class="col btn <?if($link['active']) echo 'btn-secondary'; else echo 'btn-primary';?>"
                                   href="/?t=<?=$link['code']?>">
                                    <?=$link['title']?>
                                </a>
                            </li>
                        <?endforeach;?>
                        <li>
                            <a href="/?t=login" data-toggle="modal" data-target="#loginModal">
                                <img src="https://e.unicode-table.com/orig/20/01560cec19cc7cae21069ca64727c6.png" width="35" alt="login">
                            </a>
                        </li>
                    </ul>
                    <?if(!empty($secondNav)):?>
                        <ul class="list-unstyled col d-md-flex justify-content-center mx-auto mt-md-2 mt-xl-0">
                            <? foreach($secondNav as $link):?>
                                <li class="nav-item mx-md-2 mb-2 mb-md-0">
                                    <a class="col btn <?if($link['active']) echo 'btn-secondary'; else echo 'btn-primary';?>"
                                       href="/?t=<?=$link['code']?>">
                                        <?=$link['title']?>
                                    </a>
                                </li>
                            <?endforeach;?>
                        </ul>
                    <?endif;?>
                </div>
            </nav>
            <div class="bg-dark row text-white p-2 justify-content-between flex-nowrap align-items-center">
                <a href="/">
                    <img  src="/logo.png" class="d-md-none" alt="logo">
                </a>
                <h1 class="text-overflow text-md-center col-md-10 col-lg-8 mx-auto"><?=$header?></h1>
                <button class="navbar-toggler border-0" type="button" data-toggle="collapse"
                        data-target="#nav" aria-controls="nav"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </header>
    <div id="message" class="testMessage ">
        <?if(!empty($message['info'])):?>
            <div class="alert alert-third" role="alert" >
                <?=$message['info']?>
            </div>
        <?endif;?>
        <?if(!empty($message['errors'])):?>
            <div class="alert alert-warning" role="alert" >
                <?=$message['errors']?>
            </div>
        <?endif;?>
    </div>
    <div class="row">
        <div class="col-md-10 col-lg-8 mx-auto">