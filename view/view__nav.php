<nav>
	<? foreach($nav as $link):?>
		<a class="<?if($link['active']) echo "active";?>" href="<?=$link['href']?>"><?=$link['name']?></a>
	<?endforeach;?>
</nav>