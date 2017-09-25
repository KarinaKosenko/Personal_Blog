<li>
    <div class="comment">
        <div class="author">
            <strong><?=$comment['author'];?></strong>
            <span class="date"><?=$comment['date'];?></span>           
       </div>
       <div class="comment_text"><?=$comment['text'];?></div>
       <a href = "/admin/comments/add/<?=$comment['id_article'];?>/<?=$comment['id_comment'];?>">Ответить</a><br><hr>
       <a href = "/admin/comments/edit/<?=$comment['id_article'];?>/<?=$comment['id_comment'];?>">Редактировать</a>
       <a href = "/admin/comments/delete/<?=$comment['id_article'];?>/<?=$comment['id_comment'];?>">Удалить</a><hr>
    </div>
    <?php if(!empty($comment['childs'])):?>
    <ul>
        <?php echo M\Comments::instance()->getCommentsTemplate($comment['childs']);?>
    </ul>  
    <?php endif;?>
</li>

