<?php
	session_start();
	if (!isset($_SESSION['error_data'])) {
		header("Location: http://crux.coder.tw/freedom/index.php");
		exit();
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Secura e-sim</title>
	</head>
	<body>
		<center>
			<p><?php echo $_SESSION['error_data']; ?></p>
			<a href="http://crux.coder.tw/freedom/index.php">回首頁</a>
		</center>
	</body>
</html>
<?php
	unset($_SESSION['error_data']);
?>