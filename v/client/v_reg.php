
<div class="post_section">
	<form method="post">
		Имя: *<br>
		<input type="text" name="name" value=<?=$name;?>><br>
		Логин: *<br>
		<input type="text" name="login" value=<?=$login;?>><br>
		Пароль: *<br>
		<input type="password" name="password"><br>
		Повторите пароль: *<br>
		<input type="password" name="repeat_password"><br>
		<input type="submit" value="Отправить">
	</form>
	<div>
		<?=$msg;?>
	</div>
</div>
	
	