<div class="side_column_section">
                
        <h3>Популярные статьи</h3>
<?php foreach($articles as $one): ?>
    <div class="recent_post">
        <h4><a href="/admin/articles/one/<?=$one['id_article'];?>"><?=$one['title'];?></a></h4>
        <?=substr($one['content'], 0, 100);?>
    </div>
        <?php endforeach;?>

</div>