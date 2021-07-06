<?php

	require_once(ROOT_PATH .'Controllers/maincontroller.php');
	require_once(ROOT_PATH .'Views/Players/validation.php');

	$users = new maincontroller();
	$validation = new validation();

	$label_1 = ['name', 'kana','email', 'password', 'childname', 'childkana'];
	$label_2 = ['name', 'kana', 'childname', 'childkana'];

	if(isset($_POST['submit_rgstr'])){
		foreach($_POST as $key => $val){
			$user[$key] = htmlspecialchars($val, ENT_QUOTES);
		}

		$error_empty= $validation->check_empty($label_1);
		$error_email = $validation->check_email();
		$error_len = $validation->check_len($label_2);
		if(!empty($user['password']) && $user['password'] != $user['password_c']){
			$error_pass = '同じパスワードを入力してください。';
		}

		if(empty($error_empty) && empty($error_email) && 
		   empty($error_len) && empty($error_pass)){
			
			$error_u = $users->register();
			if(empty($error_u)){
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
		<h3>新規登録</h3>
		<div class="reset_block login_u">
			<p>以下の項目を入力してください</p>
			<?php if(isset($error_u)): ?>
				<p><?=$error_u ?></p>
			<?php endif; ?>
			<form action="register.php" method="post">
				<div>
					<label class="r_label">氏名</label>
					<input type="text" name="name" value="<?php if(isset($user['name'])){echo $user['name'];} ?>">
					<?php if(isset($error_empty['name'])): ?>
						<span><?=$error_empty['name'] ?></span>
					<?php elseif(isset($error_len['name'])): ?>
						<span><?=$error_len['name'] ?></span>
					<?php endif; ?>
				</div>
				<div>
					<label class="r_label">フリガナ</label>
					<input type="text" name="kana" value="<?php if(isset($user['kana'])){echo $user['kana'];} ?>">
					<?php if(isset($error_empty['kana'])): ?>
						<span><?=$error_empty['kana'] ?></span>
					<?php elseif(isset($error_len['kana'])): ?>
						<span><?=$error_len['kana'] ?></span>
					<?php endif; ?>
				</div>
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
					<label class="r_label">パスワード</label>
					<input type="password" name="password">
					<?php if(isset($error_empty['password'])): ?>
						<span><?=$error_empty['password'] ?></span>
					<?php endif; ?>
				</div>
				<div>
					<label class="r_label">パスワード（確認）</label>
					<input type="password" name="password_c">
					<?php if(isset($error_pass)): ?>
						<span><?=$error_pass ?></span>
					<?php endif; ?>
				</div>
				<div>
					<label class="r_label">お子様の名前</label>
					<input type="text" name="childname" value="<?php if(isset($user['childname'])){echo $user['childname'];} ?>">
					<?php if(isset($error_empty['childname'])): ?>
						<span><?=$error_empty['childname'] ?></span>
					<?php elseif(isset($error_len['childname'])): ?>
						<span><?=$error_len['childname'] ?></span>
					<?php endif; ?>
				</div>
				<div>
					<label class="r_label">お子様のフリガナ</label>
					<input type="text" name="childkana" value="<?php if(isset($user['childkana'])){echo $user['childkana'];} ?>">
					<?php if(isset($error_empty['childkana'])): ?>
						<span><?=$error_empty['childkana'] ?></span>
					<?php elseif(isset($error_len['childkana'])): ?>
						<span><?=$error_len['childkana'] ?></span>
					<?php endif; ?>
				</div>
				<div class="login_b">
					<input type="submit" name="submit_rgstr" value="登録">
					<button><a href="login.php">戻る</a></button>
				</div>
			</form>
		</div>
	</main>
	<?php require_once("footer.php"); ?>
</body>