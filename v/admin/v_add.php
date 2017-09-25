<div id="main_column">
    <div>
		<?=$msg;?>
	</div>
    <br>
	<form method="post">
		Заголовок<br>
		<input type="text" name="title" style="width: 100%;" value="<?=$title;?>"><br>
                Ссылка на заглавное изображение<br>
		<input type="text" name="image_link" style="width: 100%;" value="<?=$image_link;?>"><br>
		Текст<br>
		<textarea name="content" id="text"><?=$content;?></textarea><br>
                <input type="submit" name="button" value="Отправить"><br>
	</form>
        <div class="smiles">
                <span>
                <img class="smile" src="/images/smiles/mellow.png" alt=":mellow:">
                </span>
                <span>
                <img class="smile" src="/images/smiles/dry.png" alt="&lt;_&lt;">
                </span>
                <span>
                <img class="smile" src="/images/smiles/smile.png" alt=":)">
                </span>
                <span>
                <img class="smile" src="/images/smiles/wub.png" alt=":wub:">
                </span>
                <span>
                <img class="smile" src="/images/smiles/angry.png" alt=":angry:">
                </span>
                <span>
                <img class="smile" src="/images/smiles/sad.png" alt=":(">
                </span>
                <span>
                <img class="smile" src="/images/smiles/unsure.png" alt=":unsure:">
                </span>
                <span>
                <img class="smile" src="/images/smiles/wacko.png" alt=":wacko:">
                </span>
                <span>
                <img class="smile" src="/images/smiles/blink.png" alt=":blink:">
                </span>
                <span>
                <img class="smile" src="/images/smiles/sleep.png" alt="-_-">
                </span>
                <span>
                <img class="smile" src="/images/smiles/rolleyes.gif" alt=":rolleyes:">
                </span>
                <span>
                <img class="smile" src="/images/smiles/huh.png" alt=":huh:">
                </span>
                <span>
                <img class="smile" src="/images/smiles/happy.png" alt="^_^">
                </span>
                <span>
                <img class="smile" src="/images/smiles/ohmy.png" alt=":o">
                </span>
                <span>
                <img class="smile" src="/images/smiles/wink.png" alt=";)">
                </span>
                <span>
                <img class="smile" src="/images/smiles/tongue.png" alt=":P">
                </span>
                <span>
                <img class="smile" src="/images/smiles/biggrin.png" alt=":D">
                </span>
                <span>
                <img class="smile" src="/images/smiles/laugh.png" alt=":lol:">
                </span>
                <span>
                <img class="smile" src="/images/smiles/cool.png" alt="B)">
                </span>
                <span>
                <img class="smile" src="/images/smiles/ph34r.png" alt=":ph34r:">
                </span>
            </div>
    <br>
    
    <form class="user-info" method="post" action="" enctype="multipart/form-data">
	  <input id="img" name="imgfile" type="file"><br>
	  <input class="button" value="Загрузить" type="submit">
	</form>
</div>
	
	