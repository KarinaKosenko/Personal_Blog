 <div id="main_column">
     
     <h3><strong>Статьи с тегом "<?=$name;?>"</strong></h3><hr>
    <ul> 
    <?php foreach($articles as $one):?>           
        <li><h4><a href="/admin/articles/one/<?=$one['id_article'];?>"><strong><?=$one['title'];?></strong></a></h4></li>
    <?php endforeach;?>
    </ul>   
Найдено статей: <b><?=$rows?></b><br>
				</div> <!-- end of main column --> 
		
	 
