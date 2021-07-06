<?php

	require_once(ROOT_PATH .'/Models/Db.php');

	class Diary extends Db{
		private $table1 = 'users';
		private $table2 = 'diary';
		private $table3 = 'schedule';
		private $table4 = 'comments';
		private $table5 = 'attend';
		private $table6 = 'images';

		public function __construct($dbh = null){
			parent::__construct($dbh);
		}

		public function createSchdule($diary){
			$sql = 'INSERT INTO '.$this->table3.' (date, time, place) 
					VALUES (:date, :time, :place)';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':date', $diary['date'], PDO::PARAM_STR);
			$sth->bindParam(':time', $diary['time'], PDO::PARAM_STR);
			$sth->bindParam(':place', $diary['place'], PDO::PARAM_STR);
			$sth->execute();
		}

		public function findSchedule($diary) {
			$sql = 'SELECT id, date, time, place FROM '.$this->table3.
				   ' WHERE date=:date AND time=:time';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':date', $diary['date'], PDO::PARAM_STR);
			$sth->bindParam(':time', $diary['time'], PDO::PARAM_STR);
			$sth->execute();
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			return $result;
		}

		public function deleteSchedule($diary) {
			$sql = 'DELETE FROM '.$this->table3.
				   ' WHERE date=:date AND time=:time';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':date', $diary['date'], PDO::PARAM_STR);
			$sth->bindParam(':time', $diary['time'], PDO::PARAM_STR);
			$sth->execute();
		}

		public function findById($id) {
			$sql = 'SELECT id, date, time, place FROM '.$this->table3.
				   ' WHERE id=:id';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':id', $id, PDO::PARAM_INT);
			$sth->execute();
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			return $result;
		}

		public function findByDate($date):?Array {
			$sql = 'SELECT id, date, time, place FROM '.$this->table3.' WHERE date=:date';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':date', $date, PDO::PARAM_STR);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		public function submitAttend($attend){
			$sql = 'SELECT id FROM '.$this->table5.
				   ' WHERE user_id=:user_id AND schedule_id=:schedule_id';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':user_id', $attend['user_id'], PDO::PARAM_INT);
			$sth->bindParam(':schedule_id', $attend['schedule_id'], PDO::PARAM_INT);
			$sth->execute();

			if(!($result = $sth->fetch(PDO::FETCH_ASSOC))){
				$sql = 'INSERT INTO '.$this->table5.' (user_id, schedule_id, attend, reason)
					    VALUES (:user_id, :schedule_id, :attend, :reason)';
				$sth = $this->dbh->prepare($sql);
				$sth->bindParam(':user_id', $attend['user_id'], PDO::PARAM_INT);
				$sth->bindParam(':schedule_id', $attend['schedule_id'], PDO::PARAM_INT);
				$sth->bindParam(':attend', $attend['attend'], PDO::PARAM_INT);
				$sth->bindParam(':reason', $attend['reason'], PDO::PARAM_STR);
				$sth->execute();
			}else{
				$sql = 'UPDATE '.$this->table5.' SET attend=:attend, reason=:reason 
						WHERE user_id=:user_id AND schedule_id=:schedule_id';
				$sth = $this->dbh->prepare($sql);
				$sth->bindParam(':user_id', $attend['user_id'], PDO::PARAM_INT);
				$sth->bindParam(':schedule_id', $attend['schedule_id'], PDO::PARAM_INT);
				$sth->bindParam(':attend', $attend['attend'], PDO::PARAM_INT);
				$sth->bindParam(':reason', $attend['reason'], PDO::PARAM_STR);
				$sth->execute();
			}
		}

		public function findAttend($id):?Array {
			$sql = 'SELECT S.date, S.time, attend FROM '.$this->table5.
				   ' A, '.$this->table3.' S WHERE user_id=:user_id AND 
				   A.schedule_id=S.id AND A.attend=1';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':user_id', $id, PDO::PARAM_INT);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		public function findDate():?Array {
			$today = date('Y-m-d');
			$sql = 'SELECT date, time FROM '.$this->table3.' WHERE date>=:today 
					ORDER BY date';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':today', $today, PDO::PARAM_STR);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		public function updateDiary($diary){
			$this->dbh->beginTransaction();
			try{
				$sql = 'SELECT id FROM '.$this->table2.' WHERE schedule_id=:schedule_id';
				$sth = $this->dbh->prepare($sql);
				$sth->bindParam(':schedule_id', $diary['diary']['schedule_id'], PDO::PARAM_INT);
				$sth->execute();

				if(!($result=$sth->fetch(PDO::FETCH_ASSOC))){
					$sql = 'INSERT INTO '.$this->table2.' (schedule_id, content, comment) 
							VALUES (:schedule_id, :content, :comment)';
					$sth = $this->dbh->prepare($sql);
					$sth->bindParam(':schedule_id', $diary['diary']['schedule_id'], PDO::PARAM_INT);
					$sth->bindParam(':content', $diary['diary']['content'], PDO::PARAM_STR);
					$sth->bindParam(':comment', $diary['diary']['comment'], PDO::PARAM_STR);
					$sth->execute();

					for($i=0; $i<count($diary['files']['image']['tmp_name']); $i++){
						if(!empty($diary['files']['image']['tmp_name'][$i])){
							$name = $diary['files']['image']['name'][$i];
							$type = $diary['files']['image']['type'][$i];
							$content = file_get_contents($diary['files']['image']['tmp_name'][$i]);
							$size = $diary['files']['image']['size'][$i];

							$sql = 'INSERT INTO '.$this->table6.' 
									(schedule_id, name, type, content, size) 
									VALUES (:schedule_id, :name, :type, :content, :size)';
							$sth = $this->dbh->prepare($sql);
							$sth->bindParam(':schedule_id', $diary['diary']['schedule_id'], PDO::PARAM_INT);
							$sth->bindParam(':name', $name, PDO::PARAM_STR);
							$sth->bindParam(':type', $type, PDO::PARAM_STR);
							$sth->bindParam(':content', $content, PDO::PARAM_STR);
							$sth->bindParam(':size', $size, PDO::PARAM_INT);
							$sth->execute();
						}
					}
				}else{
					$sql = 'UPDATE '.$this->table2.' SET content=:content, comment=:comment WHERE schedule_id=:schedule_id';
					$sth = $this->dbh->prepare($sql);
					$sth->bindParam(':schedule_id', $diary['diary']['schedule_id'], PDO::PARAM_INT);
					$sth->bindParam(':content', $diary['diary']['content'], PDO::PARAM_STR);
					$sth->bindParam(':comment', $diary['diary']['comment'], PDO::PARAM_STR);
					$sth->execute();

					for($i=0; $i<count($diary['files']['image']['tmp_name']); $i++){
						if(!empty($diary['files']['image']['tmp_name'][$i])){
							$name = $diary['files']['image']['name'][$i];
							$type = $diary['files']['image']['type'][$i];
							$content = file_get_contents($diary['files']['image']['tmp_name'][$i]);
							$size = $diary['files']['image']['size'][$i];

							$sql = 'INSERT INTO '.$this->table6.' 
									(schedule_id, name, type, content, size) 
									VALUES (:schedule_id, :name, :type, :content, :size)';
							$sth = $this->dbh->prepare($sql);
							$sth->bindParam(':schedule_id', $diary['diary']['schedule_id'], PDO::PARAM_INT);
							$sth->bindParam(':name', $name, PDO::PARAM_STR);
							$sth->bindParam(':type', $type, PDO::PARAM_STR);
							$sth->bindParam(':content', $content, PDO::PARAM_STR);
							$sth->bindParam(':size', $size, PDO::PARAM_INT);
							$sth->execute();
						}
					}
				}
				$this->dbh->commit();
			}catch(PDOExeption $e){
				$this->dbh->rollBack();
			}
		}

		public function findDiary($id):Array {
			$this->dbh->beginTransaction();
			try{
				$sql = 'SELECT content, comment FROM '.$this->table2.
						' WHERE schedule_id=:id';
				$sth = $this->dbh->prepare($sql);
				$sth->bindParam(':id', $id, PDO::PARAM_INT);
				$sth->execute();
				$result['diary'] = $sth->fetch(PDO::FETCH_ASSOC);

				$sql = 'SELECT * FROM '.$this->table6.
						' WHERE schedule_id=:id';
				$sth = $this->dbh->prepare($sql);
				$sth->bindParam(':id', $id, PDO::PARAM_INT);
				$sth->execute();
				$result['image_id'] = $sth->fetchAll(PDO::FETCH_ASSOC);

				$this->dbh->commit();
				return $result;
			}catch(PDOException $e){
				$this->dbh->rollBack();
			}
		}

		public function findImage($id):?Array {
			$sql = 'SELECT * FROM '.$this->table6.' WHERE id=:id';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':id', $id, PDO::PARAM_INT);
			$sth->execute();
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			return $result;
		}

		public function updateComment($params){
			$sql = 'INSERT INTO '.$this->table4.' (user_id, schedule_id, comment)
					VALUES (:user_id, :schedule_id, :comment)';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':user_id', $params['user_id'], PDO::PARAM_INT);
			$sth->bindParam(':schedule_id', $params['schedule_id'], PDO::PARAM_INT);
			$sth->bindParam(':comment', $params['comment'], PDO::PARAM_STR);
			$sth->execute();
		}

		public function findComment($id):Array {
			$sql = 'SELECT U.name, C.comment, C.create_at FROM '.$this->table1.
				   ' U, '.$this->table4.' C WHERE U.id=C.user_id AND schedule_id=:id';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':id', $id, PDO::PARAM_INT);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
	}
 ?>