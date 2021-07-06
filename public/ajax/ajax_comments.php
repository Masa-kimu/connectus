<?php
	define('ROOT_PATH', str_replace('public', '', $_SERVER["DOCUMENT_ROOT"]));
	require_once(ROOT_PATH .'/Models/Db.php');

	class Diary extends Db{
		private $table1 = 'users';
		private $table2 = 'comments';

		public function __construct($dbh = null){
			parent::__construct($dbh);
		}

		public function updateComment($params){
			$sql = 'INSERT INTO '.$this->table2.' (user_id, schedule_id, comment)
					VALUES (:user_id, :schedule_id, :comment)';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':user_id', $params['user_id'], PDO::PARAM_INT);
			$sth->bindParam(':schedule_id', $params['schedule_id'], PDO::PARAM_INT);
			$sth->bindParam(':comment', $params['comment'], PDO::PARAM_STR);
			$sth->execute();
		}

		public function findComment($id):Array {
			$sql = 'SELECT U.name, C.comment, C.create_at FROM '.$this->table1.
				   ' U, '.$this->table2.' C WHERE U.id=C.user_id AND schedule_id=:id';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':id', $id, PDO::PARAM_INT);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
	}

	$Diary = new Diary();
	if(!empty($_POST['comment'])){
		$Diary->updateComment($_POST);
	}
	$comments = $Diary->findComment($_POST['schedule_id']);

	foreach($comments as $key => $val){
		$comments[$key]['create_at'] = substr($val['create_at'], 0, -3);
	}

	foreach($comments as $keys => $vals){
		foreach($vals as $key => $val){
			$comments[$keys][$key] = htmlspecialchars($val, ENT_QUOTES);
		}
	}

	foreach($comments as $comment){
		echo '<tr>';
			echo '<td class="td_comment">'.$comment['name'].':<small>'.$comment['create_at'].'</small></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td class="td_comment_t" colspan="2">'.$comment['comment'].'</td>';
		echo '</tr>';
	}


?>