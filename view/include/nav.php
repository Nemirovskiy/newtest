    <nav>
    <? foreach($nav as $link):?>
        <a <?if($link['active']) echo 'class="nav_active"';?> href="?t=<?=$link['code']?>"><?=$link['title']?></a>
    <?endforeach;?>
    </nav>