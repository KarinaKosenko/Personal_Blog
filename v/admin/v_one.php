<div id="main_column">
    <div class="post_box">
        <h3><?=$title;?></h3>
        <div class="post_info">

            <div class="post_date">
                <?=$date;?>
          </div>

            <div class="post_author">
                <a href="#"><?=$author;?></a>
            </div>

            <div class="post_comment">
                <a href="#">184 comments</a>
            </div>

            <div class="cleaner"></div>
        </div>
            <div class="post_body">
                <?=$image_link;?>

                <p><?=$content;?></p>
    <p><a href="/admin/articles/edit/<?=$id_article;?>">Редактировать статью</a><br>
    <a href="/admin/articles/delete/<?=$id_article;?>">Удалить статью</a><br></p>
    
    Теги:
    <?=$str;?>
    
      </div>
            
  
    </div>
    
    <?=$comments;?>
</div>
