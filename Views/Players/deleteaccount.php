<?php
	
	require_once(ROOT_PATH .'Controllers/maincontroller.php');
	require_once('validation.php');

	session_start();

	if(empty($_SESSION['email'])){
		header('location: login.php');
	}elseif(!($_SESSION['role'])){
		header('location: index.php');
	}

	$Users = new maincontroller();
	$validation = new validation();
	$user = $Users->userdetail($_SESSION['id']);

	$label = ['password'];

	foreach ($user as $key => $val) {
		$user[$key] = htmlspecialchars($val, ENT_QUOTES);
	}

	if(isset($_POST['submit_delete_u'])){
		$error_empty = $validation->check_empty($label);
		if(empty($error_empty)){
			$error = $Users->deleteuser();
			if(empty($error)){
				header('location: login.php');
			}
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
	<script type="text/javascript" src="/js/base.js"></script>
</head>

<body>
	<header>
		<?php require_once("header.php"); ?>
	</header>
	<main>
		<h3>アカウント削除</h3>
		<form class="login_u login_span" name="update" action="deleteaccount.php" method="post">
			<div>
				<table>
					
						<th class="th_delete">メールアドレス</th>
						<td><?=$user['email'] ?></td>
					
				</table>
				<label class="login_label">パスワード</label>
				<input type="hidden" name="email" value="<?=$user['email'] ?>">
				<input type="password" name="password">
				<div>
					<?php if(isset($error_empty['password'])): ?>
						<span class="span_left"><?=$error_empty['password'] ?></span>
					<?php endif; ?>
				</div>
			</div>
			<div class="login_head">
				<input type="hidden" name="submit_delete_u" value="削除">
				<input class="js_open" type="submit" name="submit_delete_u" value="削除">
				<button type="button"><a href="mypage.php">戻る</a></button>
			</div>
		</form>
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