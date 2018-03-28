<!DOCTYPE html>
<html lang="ru">
<head>
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/style/style.css">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
	<title><?=$title?></title>
</head>
<body>
	<header>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h1>Сайт в разработке!</h1>
            Тесты загружены для проверки работы сайта.
            <br>
            Могут возникать ошибки. <br>
            Продолжая пользоваться сайтом, Вы соглашаетесь с этим или покиньте сайт
            <button type="button" class="btn btn-outline-secondary" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">Согласен.</span>
            </button>
        </div>
		<nav>
		<? foreach($nav as $link):?>
			<a <?if($link['active']) echo 'class="nav_active"';?> href="?t=<?=$link['code']?>"><?=$link['title']?></a>
		<?endforeach;?>
		</nav>
	</header>