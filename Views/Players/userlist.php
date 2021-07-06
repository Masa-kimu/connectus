<?php

	require_once(ROOT_PATH .'Controllers/maincontroller.php');

	session_start();
	if(empty($_SESSION['email'])){
		header('location: login.php');
	}elseif($_SESSION['role']){
		header('location: index.php');
	}

	$Users = new maincontroller();
	$params = $Users->userlist();
	
	foreach($params['users'] as $keys => $vals){
		foreach($vals as $key => $val){
			$users[$keys][$key] = htmlspecialchars($val, ENT_QUOTES);
		}
	}

	if(isset($_POST['post']['submit_delete'])){
		$Users->deleteuser();
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
		<h3>保護者一覧</h3>

		<div>
			<table class="table_user">
				<tr>
					<th class="th_user">No</th>
					<th class="th_user">氏名</th>
					<th class="th_user">メールアドレス</th>
					<th class="th_user">子どもの名前</th>
					<th class="th_user"></th>
				</tr>
				<?php if(isset($users)): ?>
					<?php foreach($users as $user): ?>
						<tr>
							<td class="td_user"><?=$user['id'] ?></td>
							<td class="td_user"><?=$user['name'] ?></td>
							<td class="td_user"><?=$user['email'] ?></td>
							<td class="td_user"><?=$user['childname'] ?></td>
							<td class="td_detail"><a href="userdetail.php?id=<?=$user['id'] ?>">詳細</a></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
			<div class="page">
				<?php
					if(isset($users)){
						for($i=0; $i<$params['pages']; $i++){
							if(isset($_GET['page']) && $_GET['page'] == $i){
								echo "<span class='span_page'>". ($i+1). "</span>";
							}elseif(!isset($_GET['page']) && $i == 0){
								echo "<span class='span_page'>". ($i+1). "</span>";
							}else{
								echo "<a class='span_page' href='?page=".$i."'>".($i+1)."</a>";
							}
						}
					}
				?>
			</div>
		</div>
	</main>
	<?php require_once("footer.php"); ?>
</body>