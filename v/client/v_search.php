 <div id="main_column">
    <?php foreach($data as $one):?>           
    <div class="post_box">

        <h3><a href="/articles/one/<?=$one['id_article'];?>"><?=$one['title'];?></a></h3>

        <div class="post_body">
                <p><?=$one['content'] = substr($one['content'], 0, 150) . '...';?></p>
    
      </div>

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
                echo "<a href=/search/page/$page/?query=$link>$page</a>";
        endif;?>
    <?php endfor;?>
    
    
</div>
				</div> <!-- end of main column --> 
		
	 