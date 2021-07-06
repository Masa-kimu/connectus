<?php

	require_once(ROOT_PATH .'Controllers/maincontroller.php');
	require_once(ROOT_PATH .'Views/Players/validation.php');

	$users = new maincontroller();
	$validation = new validation();

	$label = ['email', 'password'];

	if(isset($_POST['login'])){
		foreach($_POST as $key => $val){
			$user[$key] = htmlspecialchars($val, ENT_QUOTES);
		}

		$error_empty = $validation->check_empty($label);
		$error_email = $validation->check_email();

		if(empty($error_empty) && empty($error_email)){
			$error = $users->login();

			print($error);
			if(empty($error)){
				header('location: index.php');
			}
		}
	}

	if(isset($_GET['logout'])){
		$users->logout();
		header('location: login.php');
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
		<div class="login">
			<div class="login_u">
				<h3 class="login_head">ログイン</h3>
				<p>ユーザー名とパスワードを入力してください</p>
				<form class="login_f" action="login.php" method="post">
					<div>
						<label class="login_label">メールアドレス</label>
						<input type="text" name="email" value="<?php if(isset($user['email'])){echo $user['email'];} ?>">
					</div>
					<?php if(isset($error_empty['email'])): ?>
						<span class="login_span"><?=$error_empty['email'] ?></span>
					<?php elseif(isset($error_email)): ?>
						<span class="login_span"><?=$error_email ?></span>
					<?php endif; ?>
				
					<div>
						<label class="login_label">パスワード</label>
						<input type="password" name="password">
					</div>
					<?php if(isset($error_empty['password'])): ?>
						<span class="login_span"><?=$error_empty['password'] ?></span>
					<?php endif; ?>

					<div class="login_b">
						<input type="submit" name="login" value="ログイン">
						<button><a href="reset_password.php">パスワードを忘れた方</a></button>
					</div>
				</form>
				
			</div>
			<div class="login_u">
				<h3 class="login_head">新規登録の方はこちら</h3>
				<div class="register_b">
					<button><a href="register.php">新規登録</a></button>
				</div>
			</div>
		</div>
	</main>
	
	<?php require_once("footer.php"); ?>
</body>