<?php

	require_once(ROOT_PATH .'Controllers/diarycontroller.php');
	require_once(ROOT_PATH .'Views/Players/validation.php');

	session_start();
	header('Expires:-1');
	header('Cache-Control:');
	header('Pragma:');

	if(empty($_SESSION['email'])){
		header('location: login.php');
	}

	$diary = new diarycontroller();
	$validation = new validation();
	$label = ['date', 'time', 'place'];

	if(isset($_POST['submit_schedule'])){
		foreach ($_POST as $key => $val) {
			$daiay[$key] = htmlspecialchars($val, ENT_QUOTES);
		}

		$error_empty= $validation->check_empty($label);
		$error_len = $validation->check_len($label);

	if(empty($error_empty) && empty($error_len) && 
		$_SESSION['token'] == $_POST['token']){
			$diary->createschedule();
			foreach ($daiay as $key => $val) {
				$daiay[$key] = "";
			}
		}
	}

	if(isset($_GET['del'])){
		header('location: index.php');
	}

	if(!empty($_GET['ym'])){
		$ym = $_GET['ym'];
	}elseif(!empty($_GET['date'])){
		$date = substr($_GET['date'], 0, -3);
		$ym = date($date);
	}elseif(!empty($_POST['date'])){
		$date = substr($_POST['date'], 0, -3);
		$ym = date($date);
	}else{
		$ym = date('Y-m');
	}

	$timestamp = strtotime($ym. '-01');
	if($timestamp === false){
		$ym = date('Y-m');
		$timestamp = strtotime($ym. '-01');
	}

	$today = date('Y-m-j');

	$html_title = date('Y年m月', $timestamp);

	$prev = date('Y-m', strtotime('-1 month', $timestamp));
	$next = date('Y-m', strtotime('+1 month', $timestamp));

	$day_count = date('t', $timestamp);
	$dayofweek = date('w', $timestamp);

	$schedule = $diary->index($ym, $day_count, $_SESSION['id']);
	$attends = $schedule['attend'];

	$weeks = [];
	$week = '';

	$week .= str_repeat('<td class="td_index"></td>', $dayofweek);

	for($day=1; $day<=$day_count; $day++, $dayofweek++){
		$date = $ym. '-' .$day;

		if($today == $date){
			$week .= '<td class="td_index">'. $day;
		}else{
			$week .= '<td class="td_index">'. $day;
		}
		
		if(isset($schedule[$date])){
			foreach($schedule[$date] as $val){
				$params = $diary->diary($val['id']);
				$contents = $params['diary'];
				$week .= '<p class="para_1">'. $val['place']. '<br>'. $val['time']. '</p>';
				if(strtotime($today) <= strtotime($date)){
					if(!($_SESSION['role'])){
						$week .= "<button class='button_c'><a href='index.php?del=1&date=".$val['date']."&time=".$val['time']."'>削除</a></button>";
					}else{
						$week .= "<button class='button_c'><a href='attendance.php?date=".$val['date']."&time=".$val['time']."'>出欠連絡</a></button>";
						foreach($attends as $attend){
							if(($attend['date'] == $val['date'])
							   && ($attend['time'] == $val['time'])){
							   	$week .= "<span>欠席</span>";
							}
						}
					}
				}else{
					if(!($_SESSION['role']) && empty($contents)){
						$week .= "<button class='button_c'><a href='editdiary.php?date=".$val['date']."&time=".$val['time']."'>日誌作成</a></button>";
					}elseif(!($_SESSION['role']) && !empty($contents)){
						$week .= "<button class='button_c'><a href='editdiary.php?date=".$val['date']."&time=".$val['time']."'>日誌編集</a></button>";
					}
					if(!empty($contents)){
						$week .= "<button class='button_c'><a href='diary.php?date=".$val['date']."&time=".$val['time']."'>閲覧</a></button>";
					}
				}
			}
		}


		$week .= '</td>';

		if($dayofweek % 7 == 6 || $day == $day_count){
			if($day == $day_count){
				$week .= str_repeat('<td td_index></td>', 6 - ($dayofweek % 7));
			}
			$weeks[] = '<tr>'. $week. '</tr>';
			$week = '';
		}
	}

	$_SESSION['token'] = $token = mt_rand();

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
		<h3>活動日誌</h3>
		<?php if(!($_SESSION['role'])): ?>
			
			<div class="login_u login_span">
				<h4 class="login_head">***スケジュール作成***</h4>
				<form class="form_index" action="index.php" method="post">
					<div class="flex_1">
						<label>日にち : </label>
						<input class="input_schdl" type="date" name="date" value="<?php if(isset($daiay['date'])){echo $daiay['date'];} ?>">
						<?php if(isset($error_empty['date'])): ?>
							<span><?=$error_empty['date'] ?></span>
						<?php elseif(isset($error_len['date'])): ?>
							<span><?=$error_len['date'] ?></span>
						<?php endif; ?>
					</div>

					<div class="flex_1">
						<label>時間 : </label>
						<input class="input_schdl" type="text" name="time" value="<?php if(isset($daiay['time'])){echo $daiay['time'];} ?>">
						<?php if(isset($error_empty['time'])): ?>
							<span><?=$error_empty['time'] ?></span>
						<?php elseif(isset($error_len['time'])): ?>
							<span><?=$error_len['time'] ?></span>
						<?php endif; ?>
					</div>

					<div class="flex_1">
						<label>場所 : </label>
						<input class="input_schdl" type="text" name="place" value="<?php if(isset($daiay['place'])){echo $daiay['place'];} ?>">
						<?php if(isset($error_empty['place'])): ?>
							<span><?=$error_empty['place'] ?></span>
						<?php elseif(isset($error_len['place'])): ?>
							<span><?=$error_len['place'] ?></span>
						<?php endif; ?>
					</div>
					<div class="login_head">
						<input type="hidden" name="token" value="<?=$token ?>">
						<input type="submit" name="submit_schedule" value="登録">
					</div>
				</form>
			</div>
		<?php endif; ?>

		<div id="calender">
			<div class="login_head">
				<h5><a href="?ym=<?=$prev ?>">&lt</a><?=$html_title ?><a href="?ym=<?=$next ?>">&gt</a></h5>
			</div>
			<table class="table_index">
				<tr>
					<th class="th_index">日</th>
					<th class="th_index">月</th>
					<th class="th_index">火</th>
					<th class="th_index">水</th>
					<th class="th_index">木</th>
					<th class="th_index">金</th>
					<th class="th_index">土</th>
				</tr>
					<?php foreach($weeks as $week){
						echo $week;
					}?>
			</table>
		</div>

	</main>
	<?php require_once("footer.php"); ?>
</body>