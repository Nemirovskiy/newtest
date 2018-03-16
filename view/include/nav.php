    <nav>
    <? foreach($nav as $link):?>
        <a <?if($link['active']) echo 'class="active"';?> href="?t=<?=$link['code']?>"><?=$link['title']?></a>
    <?endforeach;?>
    </nav>