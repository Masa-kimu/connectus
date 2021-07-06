<div class="head">
	<h1><a class="link_h" href="index.php">ConnectUs</a></h1>
	<div class="head_nav">
		<?php if(!($_SESSION['role'])): ?>
			<button class="button_h"><a class="link_h" href="absencee.php">出欠確認</a></button>
			<button class="button_h"><a class="link_h" href="userlist.php">保護者一覧</a></button>
		<?php else: ?>
			<button class="button_h"><a class="link_h" href="mypage.php">マイページ</a></button>
		<?php endif; ?>

		<button class="button_h"><a class="link_h" href="login.php?logout=1">ログアウト</a></button>
	</div>
</div>