<?php
	
	require_once(ROOT_PATH .'Controllers/diarycontroller.php');
	require_once('validation.php');

	session_start();

	if(empty($_SESSION['email'])){
		header('location: login.php');
	}elseif(!($_SESSION['role'])){
		header('location: index.php');
	}

	$diary = new diarycontroller();
	$validation = new validation();

	$label = ['reason'];

	$schedule = $diary->schedule();
	foreach($schedule as $key => $val) {
		$schedule[$key] = htmlspecialchars($val, ENT_QUOTES);
	}
	if(!empty($_POST)){
		foreach($_POST as $key => $val) {
			$attend[$key] = htmlspecialchars($val, ENT_QUOTES);
		}
		$error_empty= $validation->check_empty($label);
		if(empty($error_empty)){
			$diary->submitattend();
			header('location: index.php');
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
		<h3>出欠連絡</h3>
		<div class="login_u login_span">
			<table class="table_userd">
				<tr>
					<th class="th_userd">日にち</th>
					<td class="td_userd"><?=$schedule['date'] ?></td>
				</tr>
				<tr>
					<th class="th_userd">時間</th>
					<td class="td_userd"><?=$schedule['time'] ?></td>
				</tr>
				<tr>
					<th class="th_userd">場所</th>
					<td class="td_userd"><?=$schedule['place'] ?></td>
				</tr>
			</table>

			<form action="attendance.php" name="update" method="post">
				<div>
					<label>出欠</label>
					<select class="select_attnd" name="attend">
						<option value="0">出席</option>
						<option value="1" selected>欠席</option>
					</select>
				</div>
				<div>
					<label>理由</label>
					<textarea class="area_attnd" name="reason"></textarea>
					<?php if(isset($error_empty['reason'])): ?>
						<span><?=$error_empty['reason'] ?></span>
					<?php endif; ?>
				</div>
				<div class="login_head">
					<input type="hidden" name="schedule_id" value="<?=$schedule['id'] ?>">
					<input type="hidden" name="user_id" value="<?=$_SESSION['id'] ?>">
					<input type="hidden" name="submit_attn" >
					<input class="js_open" type="submit" name="submit_attn" >
					<button type="button"><a href="index.php">戻る</a></button>
				</div>
			</form>
		</div>
	</main>
	<div class="modal js_modal_open">
		<div class="modal_bg js_modal_close"></div>
		<div class="modal_box">
			<p class="login_head">送信しますか？</p>
			<button class="modal_ok">OK</button>
			<button class="modal_cncl js_modal_close">キャンセル</button>
		</div>
	</div>
	<?php require_once("footer.php"); ?>
</body>