<?php
	
	require_once(ROOT_PATH .'Controllers/maincontroller.php');

	session_start();
	if(empty($_SESSION['email'])){
		header('location: login.php');
	}elseif(!($_SESSION['role'])){
		header('location: index.php');
	}

	$Users = new maincontroller();
	$user = $Users->userdetail($_SESSION['id']);

	foreach ($user as $key => $val) {
		$user[$key] = htmlspecialchars($val, ENT_QUOTES);
	}
	
?>


<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">

	<title>login</title>
	<meta name="robots" content="noindex, nofollow">

	<link rel="stylesheet" href="/css/base.css">
</head>

<body>
	<header>
		<?php require_once("header.php"); ?>
	</header>
	<main>
		<h3>マイページ</h3>	
		<div class="login_span">	
			<div class="login_u login_span">
				<table class="table_userd">
					<tr>
						<th class="th_userd">氏名</th>
						<td class="td_userd"><?=$user['name'] ?></td>
					</tr>
					<tr>
						<th class="th_userd">フリガナ</th>
						<td class="td_userd"><?=$user['kana'] ?></td>
					</tr>
					<tr>
						<th class="th_userd">メールアドレス</th>
						<td class="td_userd"><?=$user['email'] ?></td>
					</tr>
					<tr>
						<th class="th_userd">お子様の名前</th>
						<td class="td_userd"><?=$user['childname'] ?></td>
					</tr>
					<tr>
						<th class="th_userd">お子様のフリガナ</th>
						<td class="td_userd"><?=$user['childkana'] ?></td>
					</tr>
				</table>
			</div>
			<div class="login_head">
				<button><a href="editmypage.php">編集</a></button>
				<button><a href="deleteaccount.php">アカウント削除</a></button>
				<button><a href="index.php">戻る</a></button>
			</div>
		</div>
	</main>
	<?php require_once("footer.php"); ?>
</body>