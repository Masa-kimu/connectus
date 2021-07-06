<?php
	define('ROOT_PATH', str_replace('public', '', $_SERVER["DOCUMENT_ROOT"]));
	require_once(ROOT_PATH .'/Models/Db.php');

	class Diary extends Db{
		private $table1 = 'users';
		private $table2 = 'schedule';
		private $table3 = 'attend';

		public function __construct($dbh = null){
			parent::__construct($dbh);
		}

		public function findAllAttend($date):?Array {
			$sql = 'SELECT name, childname, reason FROM '.$this->table1.' U, '
				   .$this->table3.' A WHERE A.user_id=U.id AND 
				    schedule_id=(SELECT id FROM '.$this->table2.
				    ' WHERE date=:date AND time=:time) AND attend=1';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':date', $date[0], PDO::PARAM_STR);
			$sth->bindParam(':time', $date[1], PDO::PARAM_STR);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
	}

	$Diary = new Diary();

	$date = explode('/', $_POST['date']);
	$absencees = $Diary->findAllAttend($date);
	foreach($absencees as $keys => $vals){
		foreach($vals as $key => $val){
			$user[$keys][$key] = htmlspecialchars($val, ENT_QUOTES);
		}
	}
	if(!empty($_POST['date'])){
		if(!empty($user)){
			echo '<tr>';
				echo '<th class="th_abs">No.</th>';
				echo '<th class="th_abs">氏名</th>';
				echo '<th class="th_abs">子どもの名前</th>';
				echo '<th class="th_abs">理由</th>';
			echo '</tr>';
			for($i=0; $i<count($user); $i++){
				echo '<tr>';
					echo '<td class="td_abs">'.($i+1).'</td>';
					echo '<td class="td_abs">'.$user[$i]['name'].'</td>';
					echo '<td class="td_abs">'.$user[$i]['childname'].'</td>';
					echo '<td class="td_abs">'.$user[$i]['reason'].'</td>';
				echo '</tr>';
			}
		}else{
			echo '<tr>';
				echo '<td class="td_abs">欠席者なし</td>';
			echo '</tr>';
		}
	}else{
		//echo "";
	}

?>