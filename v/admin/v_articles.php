 <div id="main_column">
     
     <h2><a href="/admin/articles/add">Добавить новую статью</a></h2><hr>
     
    <?php foreach($data as $one):?>           
    <div class="post_box">

        <h3><a href="/admin/articles/one/<?=$one['id_article'];?>"><?=$one['title'];?></a></h3>

  <div class="post_info">

            <div class="post_date">
                <?=$one['date'];?>
          </div>

            <div class="post_author">
                <a href="#"><?=$one['author'];?></a>
            </div>

            <div class="post_comment">
                <a href="#">184 comments</a>
            </div>

            <div class="cleaner"></div>
        </div>

        <div class="post_body">
           <?=$one['image_link'];?>

                <p><?=$smile->smile($one['content'] = substr($one['content'], 0, 200) . '...');?></p>
      </div>

      <div class="continue"><a href="/admin/articles/one/<?=$one['id_article'];?>">Дальше</a></div>

    </div> <!-- end of a post -->

<?php endforeach;?>
    
Найдено статей: <b><?=$rows?></b><br>

<div>
    Страницы: 
    <?php for($page = 1; $page <= $num_pages; $page++):?>
        <?php 
            if($page == $cur_page):
                echo "<b>$page</b> ";
            else:
                echo "<a href=/admin/articles/page/$page>$page</a>";
        endif;?>
    <?php endfor;?>
    
    
</div>
				</div> <!-- end of main column --> 
		
	 