<?php
	
	require_once(ROOT_PATH .'Controllers/maincontroller.php');

	session_start();
	if(empty($_SESSION['email'])){
		header('location: login.php');
	}elseif($_SESSION['role']){
		header('location: index.php');
	}

	$Users = new maincontroller();
	$user = $Users->userdetail();

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
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js"
			integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
			crossorigin="anonymous"></script>
	<script type="text/javascript" src="/js/base.js"></script>
</head>

<body>
	<header>
		<?php require_once("header.php"); ?>
	</header>
	<main>
		<h3>保護者詳細</h3>
		<div class="login_u login_span">
			<div>
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
			<div>
				<form class="login_head" name="update" action="userlist.php" method="post">
					<input class="js_open button_delete" type="submit" name="submit_delete" value="削除">
					<button type="button"><a href="userlist.php">戻る</a></button>

					<input type="hidden" name="email" value="<?=$user['email'] ?>">
					<input type="hidden" name="submit_delete" value="削除">
				</form>
			</div>
		</div>
	</main>
	<div class="modal js_modal_open">
		<div class="modal_bg js_modal_close"></div>
		<div class="modal_box">
			<p class="login_head">削除しますか？</p>
			<button class="modal_ok">OK</button>
			<button class="modal_cncl js_modal_close">キャンセル</button>
		</div>
	</div>
	<?php require_once("footer.php"); ?>
</body>