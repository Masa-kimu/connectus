<?php
	
	require_once(ROOT_PATH .'Controllers/diarycontroller.php');

	session_start();

	if(empty($_SESSION['email'])){
		header('location: login.php');
	}elseif($_SESSION['role']){
		header('location: index.php');
	}


	$diary = new diarycontroller();
	$dates = $diary->findDate();
	foreach($dates as $keys => $vals){
		foreach($vals as $key => $val){
			$dates[$keys][$key] = htmlspecialchars($val, ENT_QUOTES);
		}
	}

?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">

	<title>login</title>
	<meta name="robots" content="noindex, nofollow">

	<link rel="stylesheet" href="/css/base.css">
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js"
			integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
			crossorigin="anonymous"></script>
	<script type="text/javascript" src="/js/ajax.js"></script>
</head>

<body>
	<header>
		<?php require_once("header.php"); ?>
	</header>
	<main>
		<h3>欠席者一覧</h3>
		<form action="absencee.php" method="post">
			<select class="ajax_abs list_abs" name='date'>
				<option value="">選択してください。</option>
				<?php foreach($dates as $date): ?>
					<option value="<?=$date['date'].'/'.$date['time'] ?>"
						<?php if(!empty($_POST) && $dated[0] == $date['date'] && $dated[1] == $date['time']){echo 'selected';} ?>><?=$date['date'].' / '.$date['time'] ?></option>
				<?php endforeach; ?>
			</select>
		</form>

		<table class="ajax_a table_abs">
		</table>
	</main>
	<?php require_once("footer.php"); ?>
</body>