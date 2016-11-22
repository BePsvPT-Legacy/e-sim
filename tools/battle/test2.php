<?php
	function test($apple) {
		if (is_null($apple)) {
			echo 'ok';
		} else {
			echo 'no';
		}
	}
	
	test($_POST['www']);
?>