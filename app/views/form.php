<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<br><br><br>	
	<form method="post">
		<?= csrf() ?>
		<input type="text" name="username" placeholder="Username">
		<input type="password" name="password" placeholder="Password">
		<input type="submit">
	</form>
</body>
</html>