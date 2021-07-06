<?php
	
	require_once(ROOT_PATH .'Controllers/diarycontroller.php');
	require_once(ROOT_PATH .'Views/Players/validation.php');

	session_start();
	if(empty($_SESSION['email'])){
		header('location: login.php');
	}elseif($_SESSION['role']){
		header('location: index.php');
	}

	$Diary = new diarycontroller();
	$validation = new validation();
	$label = ['content', 'comment'];

	$diary['date'] = $Diary->schedule();
	$params = $Diary->diary($diary['date']['id']);
	if(empty($_POST)){
		$diary['contents'] = $params['diary'];
	}
	$images = $params['image_id'];

	foreach($diary['date'] as $key => $val){
		$diary['date'][$key] = htmlspecialchars($val, ENT_QUOTES);
	}

	if(!empty($diary['contents'])){
		foreach($diary['contents'] as $key => $val){
			$diary['contents'][$key] = htmlspecialchars($val, ENT_QUOTES);
		}
	}

	if(!empty($_POST)){
		foreach($_POST as $key => $val){
			$contents[$key] = htmlspecialchars($val, ENT_QUOTES);
		}
		$error_empty= $validation->check_empty($label);
		if(empty($error_empty)){
			$params = $Diary->editdiary();
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
	<script type="text/javascript" src="/js/ajax.js"></script>
	<script type="text/javascript" src="/js/base.js"></script>
</head>

<body>
	<header>
		<?php require_once("header.php"); ?>
	</header>
	<main>
		<h3>日誌作成・編集</h3>
		<div class="login_u">
			<table>
				<tr>
					<th class="th_diary">日にち</th>
					<td class="th_diary"><?=$diary['date']['date'] ?></td>
				</tr>
				<tr>
					<th class="th_diary">時間</th>
					<td class="th_diary"><?=$diary['date']['time'] ?></td>
				</tr>
				<tr>
					<th class="th_diary">場所</th>
					<td class="th_diary"><?=$diary['date']['place'] ?></td>
				</tr>
			</table>
			<form action="editdiary.php" name="update" method="post" enctype="multipart/form-data">
				<div>
					<label class="d_label">内容</label>
					<textarea class="area_diary" name="content"><?php if(isset($diary['contents']['content'])){echo $diary['contents']['content'];}elseif(isset($contents['content'])){echo $contents['content'];} ?></textarea>
					<?php if(isset($error_empty['content'])): ?>
						<span><?=$error_empty['content'] ?></span>
					<?php endif; ?>
				</div>
				<div>
					<label class="d_label">コメント</label>
					<textarea class="area_diary" name="comment"><?php if(isset($diary['contents']['comment'])){echo $diary['contents']['comment'];}elseif(isset($contents['comment'])){echo $contents['comment'];} ?></textarea>
					<?php if(isset($error_empty['comment'])): ?>
						<span><?=$error_empty['comment'] ?></span>
					<?php endif; ?>
				</div>

				<div>
					<label>写真</label>
					<div class="imgs_block">
						<?php if(!empty($images)): ?>
							<?php for($i=0; $i<count($images); $i++): ?>
								<div class="img_size">
									<img src="image.php?id=<?=$images[$i]['id'] ?>">
									<input type="hidden" name="dlt" value="<?=$images[$i]['id'] ?>">
									<span class="ajax_dlt dlt">削除</span>
								</div>
							<?php endfor; ?>
						<?php endif; ?>
					</div>
					<div class="imgs_block">
						<div class="imgs">
							<label class="label_img">画像を追加
								<input class="upimg js_img" type="file" name="image[]" accept="image/*" >
							</label>
							<img class="preview1">
						</div>
					</div>
				</div>

				<input type="hidden" name="schedule_id" value="<?=$diary['date']['id'] ?>">
				<input type="hidden" name="submit_diary" value="登録">
				<div class="login_head">
					<input class="js_open" type="submit" name="submit_diary" value="登録">
					<button class="b_h" type="button"><a href="index.php">戻る</a></button>
				</div>
			</form>
		</div>
	</main>
	<div class="modal js_modal_open">
		<div class="modal_bg js_modal_close"></div>
		<div class="modal_box">
			<p class="login_head">登録しますか？</p>
			<button class="modal_ok">OK</button>
			<button class="modal_cncl js_modal_close">キャンセル</button>
		</div>
	</div>
	<?php require_once("footer.php"); ?>
</body>