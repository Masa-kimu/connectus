<?php

	require_once(ROOT_PATH .'/Models/Diary.php');

	$Diary = new Diary();
	$image = $Diary->findImage($_GET['id']);

	header('Content-type'. $image['type']);
	echo $image['content'];
	exit();

?>