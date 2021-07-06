<?php
	
	require_once(ROOT_PATH .'Controllers/maincontroller.php');
	require_once(ROOT_PATH .'Views/Players/validation.php');

	$Users = new maincontroller();
	$validation = new validation();

	session_start();
	if(empty($_SESSION['email'])){
		header('location: login.php');
	}elseif(!($_SESSION['role'])){
		header('location: index.php');
	}

	$user = $Users->userdetail($_SESSION['id']);

	$label_1 = ['name', 'kana','email', 'childname', 'childkana'];
	$label_2 = ['name', 'kana', 'childname', 'childkana'];	

	if(isset($_POST['submit_edituser'])){
		foreach ($_POST as $key => $val) {
			$user[$key] = htmlspecialchars($val, ENT_QUOTES);
		}

		$error_empty= $validation->check_empty($label_1);
		$error_email = $validation->check_email();
		$error_len = $validation->check_len($label_2);

		if(empty($error_empty) && empty($error_email) && 
		   empty($error_len)){
			
			$error_u = $Users->edituser();
			header('location: mypage.php');
		}
	}else{
		foreach ($user as $key => $val) {
			$user[$key] = htmlspecialchars($val, ENT_QUOTES);
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
		<h3>マイページ編集</h3>
		<form class="login_u login_span" name="update" action="editmypage.php" method="post">
			<div>
				<label class="r_label">氏名</label>
				<input type="text" name="name" value=
				"<?php if(isset($user['name'])){echo $user['name'];} ?>">
				<?php if(isset($error_empty['name'])): ?>
					<span><?=$error_empty['name'] ?></span>
				<?php elseif(isset($error_len['name'])): ?>
					<span><?=$error_len['name'] ?></span>
				<?php endif; ?>
			</div>
			<div>
				<label class="r_label">フリガナ</label>
				<input type="text" name="kana" value=
				"<?php if(isset($user['kana'])){echo $user['kana'];} ?>">
				<?php if(isset($error_empty['kana'])): ?>
					<span><?=$error_empty['kana'] ?></span>
				<?php elseif(isset($error_len['kana'])): ?>
					<span><?=$error_len['kana'] ?></span>
				<?php endif; ?>
			</div>
			<div>
				<label class="r_label">メールアドレス</label>
				<input type="text" name="email" value=
				"<?php if(isset($user['email'])){echo $user['email'];} ?>">
				<?php if(isset($error_empty['email'])): ?>
					<span><?=$error_empty['email'] ?></span>
				<?php elseif(isset($error_email)): ?>
					<span><?=$error_email ?></span>
				<?php endif; ?>
			</div>
			<div>
				<label class="r_label">お子様の名前</label>
				<input type="text" name="childname" value=
				"<?php if(isset($user['childname'])){echo $user['childname'];} ?>">
				<?php if(isset($error_empty['childname'])): ?>
					<span><?=$error_empty['childname'] ?></span>
				<?php elseif(isset($error_len['childname'])): ?>
					<span><?=$error_len['childname'] ?></span>
				<?php endif; ?>
			</div>
			<div>
				<label class="r_label">お子様のフリガナ</label>
				<input type="text" name="childkana" value=
				"<?php if(isset($user['childkana'])){echo $user['childkana'];} ?>"> 
				<?php if(isset($error_empty['childkana'])): ?>
					<span><?=$error_empty['childkana'] ?></span>
				<?php elseif(isset($error_len['childkana'])): ?>
					<span><?=$error_len['childkana'] ?></span>
				<?php endif; ?>
			</div>

			<div class="login_head">
				<input type="hidden" name="submit_edituser" value="更新">
				<input class="js_open" type="submit" name="submit_edituser" value="更新">
				<button type="button"><a href="mypage.php">戻る</a></button>
			</div>
		</form>
	</main>
	<div class="modal js_modal_open">
		<div class="modal_bg js_modal_close"></div>
		<div class="modal_box">
			<p class="login_head">更新しますか？</p>
			<button class="modal_ok">OK</button>
			<button class="modal_cncl js_modal_close">キャンセル</button>
		</div>
	</div>
	<?php require_once("footer.php"); ?>
</body>