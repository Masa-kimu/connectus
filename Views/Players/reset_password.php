<?php

	require_once(ROOT_PATH .'Controllers/maincontroller.php');
	require_once(ROOT_PATH .'Views/Players/validation.php');

	$users = new maincontroller();
	$validation = new validation();

	$label = ['email', 'password'];

	if(isset($_POST['resetpswd'])){
		foreach($_POST as $key => $val){
			$user[$key] = htmlspecialchars($val, ENT_QUOTES);
		}

		$error_empty = $validation->check_empty($label);
		$error_email = $validation->check_email();
		if(!empty($user['password']) && $user['password'] != $user['password_c']){
			$error_pass = '新パスワードと同じものを入力してください。';
		}

		if(empty($error_empty) && empty($error_email) && empty($error_pass)){
			$error = $users->resetpswd();
			if(empty($error)){
				header('location: index.php');
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

</head>

<body>
	<header>
		<h1><a href="login.php">ConnectUs</a></h1>
	</header>
	<main>
		<h3>パスワード再設定</h3>
		<div class="reset_block login_u">
			<p>以下の項目を入力してください</p>
			<form action="reset_password.php" method="post">
				<div>
					<label class="r_label">メールアドレス</label>
					<input type="text" name="email" value="<?php if(isset($user['email'])){echo $user['email'];} ?>">
					<?php if(isset($error_empty['email'])): ?>
						<span><?=$error_empty['email'] ?></span>
					<?php elseif(isset($error_email)): ?>
						<span><?=$error_email ?></span>
					<?php endif; ?>
				</div>
				<div>
					<label class="r_label">新パスワード</label>
					<input type="password" name="password">
					<?php if(isset($error_empty['password'])): ?>
						<span><?=$error_empty['password'] ?></span>
					<?php endif; ?>
				</div>
				<div>
					<label class="r_label">新パスワード（確認）</label>
					<input type="password" name="password_c">
					<?php if(isset($error_pass)): ?>
						<span><?=$error_pass ?></span>
					<?php endif; ?>
				</div>
				<div class="login_b">
					<input type="submit" name="resetpswd" value="再登録">
					<button><a href="login.php">戻る</a></button>
				</div>
			</form>
		</div>
	</main>

	<?php require_once("footer.php"); ?>
</body>