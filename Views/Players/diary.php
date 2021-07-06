<?php
	
	require_once(ROOT_PATH .'Controllers/diarycontroller.php');

	session_start();

	$Diary = new diarycontroller();

	$diary['date'] = $Diary->schedule();
	$params = $Diary->diary($diary['date']['id']);

	$diary['contents'] = $params['diary'];
	$images = $params['image_id'];

	foreach($diary as $keys => $vals){
		foreach($vals as $key => $val){
			$diary[$keys][$key] = htmlspecialchars($val, ENT_QUOTES);
		}
	}

	$comments = $Diary->comments($diary['date']['id']);
	foreach($comments as $key => $val){
		$comments[$key]['create_at'] = substr($val['create_at'], 0, -3);
	}

	foreach($comments as $keys => $vals){
		foreach($vals as $key => $val){
			$comments[$keys][$key] = htmlspecialchars($val, ENT_QUOTES);
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
	<link href="/css/lightbox.css" rel="stylesheet">
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js"
			integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
			crossorigin="anonymous"></script>
	<script type="text/javascript" src="/js/ajax.js"></script>
	<script src="/js/lightbox.js"></script>
	<script>
    lightbox.option({
      'alwaysShowNavOnTouchDevices': true,
      'wrapAround': true
    })
	</script>
</head>

<body>
	<header>
		<?php require_once("header.php"); ?>
	</header>
	<main>
		<h3>活動日誌</h3>
		<div class="login_u login_span">
			<table class="table_userd">
				<tr>
					<th class="th_userd">日にち</th>
					<td class="td_userd"><?=$diary['date']['date'] ?></td>
				</tr>
				<tr>
					<th class="th_userd">時間</th>
					<td class="td_userd"><?=$diary['date']['time'] ?></td>
				</tr>
				<tr>
					<th class="th_userd">場所</th>
					<td class="td_userd"><?=$diary['date']['place'] ?></td>
				</tr>
				<tr>
					<th class="th_userd">内容</th>
					<td class="td_userd"><?=$diary['contents']['content'] ?></td>
				</tr>
				<tr>
					<th class="th_userd">コメント</th>
					<td class="td_userd"><?=$diary['contents']['comment'] ?></td>
				</tr>
			</table>
			<p class="para_img">写真</p>
			<div class="imgs_block">
				<?php if(!empty($images)): ?>
					<?php for($i=0; $i<count($images); $i++): ?>
						<div class="img_size">
							<a href="image.php?id=<?=$images[$i]['id'] ?>" data-lightbox="roadtrip"><img src="image.php?id=<?=$images[$i]['id'] ?>"></a>
						</div>
					<?php endfor; ?>
				<?php endif; ?>
			</div>
		</div>
		<div>
			<h3>掲示板</h3>
			<div class="login_u">
				<table class="ajax_a">
					<?php foreach($comments as $comment): ?>
						<tr>
							<td class="td_comment"><?=$comment['name'] ?>:<small><?=$comment['create_at'] ?></small></td>
						</tr>
						<tr>
							<td class="td_comment_t" colspan="2"><?=$comment['comment'] ?></td>
						</tr>
					<?php endforeach; ?>
				</table>

			<form action="diary.php" method="post">
				<div>
					<label>コメント投稿</label>
					<textarea class="areasize_c" name="comment"></textarea>
				</div>
				<input type="hidden" name="schedule_id" value="<?=$diary['date']['id'] ?>">
				<input type="hidden" name="user_id" value="<?=$_SESSION['id'] ?>">
				<input type="hidden" name="num" value="<?=$num ?>">
				<div class="login_b">
					<input class="ajax_comment" type="submit" name="submit_comment">
				</div>
			</form>
			</div>
		</div>
	</main>
	<?php require_once("footer.php"); ?>
</body>