<?php
	if (isset($_GET["message"])) {
		
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
<?php
	if (!ADMIN_ONLY) {
?>
		<meta http-equiv="refresh" content="2;url=http://crux.coder.tw/freedom/index.php">
<?php
	}
?>
		<title>e-sim - Omnipotent Taiwan</title>
		<link rel="icon" href="http://crux.coder.tw/freedom/images/error.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="http://crux.coder.tw/freedom/scripts/css/pure-min.css">
	</head>
	<body>
		<div class="heading_center heading_highlight">
			<h1><?php echo $_GET["message"]; ?></h1>
		</div>
	</body>
</html>
<?php
	} else {
		header("Location: http://crux.coder.tw/freedom/index.php");
	}
?>