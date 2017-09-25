 <div id="main_column">
    <?php foreach($data as $one):?>           
    <div class="post_box">

        <h2><a href="/articles/one/<?=$one['id_article'];?>"><?=$one['title'];?></a></h2>

  <div class="post_info">

            <div class="post_date">
                <?=$one['date'] = substr($one['date'], 0, 10);?>
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

                <a href="#"><img src="/images/templatemo_image_01.jpg" alt="free template" /></a>

                <p><?=$one['content'] = substr($one['content'], 0, 150) . '...';?></p>
    <p></p>

      </div>

      <div class="continue"><a href="/articles/one/<?=$one['id_article'];?>">Continue</a></div>

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
                echo "<a href=/articles/page/$page>$page</a>";
        endif;?>
    <?php endfor;?>
    
    
</div>
				</div> <!-- end of main column --> 
		
	 