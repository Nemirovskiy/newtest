<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="/style/style.css">
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
	<title><?=$title?></title>
</head>
<body>
	<header>
		<nav>
		<? foreach($nav as $link):?>
			<a <?if($link['active']) echo 'class="active"';?> href="?t=<?=$link['name']?>"><?=$link['title']?></a>
		<?endforeach;?>
		</nav>
	</header>