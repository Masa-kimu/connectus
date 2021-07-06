<?php

	require_once(ROOT_PATH .'/Models/Db.php');

	class Users extends Db{
		private $table1 = 'users';

		public function __construct($dbh = null){
			parent::__construct($dbh);
		}

		public function loginUser($user):?String {
			try{
				$sql = 'SELECT email, password, role FROM '.$this->table1.
					   ' WHERE email = :email';
				$sth = $this->dbh->prepare($sql);
				$sth->bindParam(':email', $user['email'], PDO::PARAM_STR);
				$sth->execute();
				
				if($result = $sth->fetch(PDO::FETCH_ASSOC)){
					if(password_verify($user['password'], $result['password'])){
						session_start();
						$_SESSION['email'] = $result['email'];
						$_SESSION['role'] = $result['role'];
						return null;
					}else{
						$error = 'メールアドレスもしくはパスワードが間違っています。';
						return $error;
					}
				}else{
					$error = 'メールアドレスもしくはパスワードが間違っています。';
						return $error;
				}
			}catch(PDOExeption $e){
				$error = 'データベースエラー';
				return $error;
			}
		}

		public function registerUser($user):?String {
			$user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

			$sql = 'SELECT id, email FROM '.$this->table1.' WHERE email = :email';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':email', $user['email'], PDO::PARAM_STR);
			$sth->execute();

			try{
				if(!($result = $sth->fetch(PDO::FETCH_ASSOC))){
					$sql = 'INSERT INTO '.$this->table1. 
					' (name, kana, email, password, childname, childkana) 
					VALUES (:name, :kana, :email, :password, :childname, :childkana)';
					$sth = $this->dbh->prepare($sql);
					$sth->bindParam(':name', $user['name'], PDO::PARAM_STR);
					$sth->bindParam(':kana', $user['kana'], PDO::PARAM_STR);
					$sth->bindParam(':email', $user['email'], PDO::PARAM_STR);
					$sth->bindParam(':password', $user['password'], PDO::PARAM_STR);
					$sth->bindParam(':childname', $user['childname'], PDO::PARAM_STR);
					$sth->bindParam(':childkana', $user['childkana'], PDO::PARAM_STR);
					$sth->execute();
					return null;
				}else{
					$error='登録済みのメールアドレスです。<br>違うメールアドレスを入力してください。';
					return $error;
				}
			}catch(PDOExeption $e){
				$error = 'データベースエラー';
				return $error;
			}

		}

		public function resetpswd($user):?String {
			try{
				$sql = 'SELECT id, password FROM '.$this->table1.' WHERE email = :email';
				$sth = $this->dbh->prepare($sql);
				$sth->bindParam(':email', $user['email'], PDO::PARAM_STR);
				$sth->execute();

				if($result = $sth->fetch(PDO::FETCH_ASSOC)){
					$user['password'] = password_hash($user['password'], 
										PASSWORD_DEFAULT);
					$sql = 'UPDATE '.$this->table1.
						   ' SET password = :password WHERE email = :email';
					$sth = $this->dbh->prepare($sql);
					$sth->bindParam(':password', $user['password'], PDO::PARAM_STR);
					$sth->bindParam(':email', $user['email'], PDO::PARAM_STR);
					$sth->execute();
					return null;
				}else{
					$error = 'メールアドレスもしくは現在のパスワードが間違っています。';
					return $error;
				}
			}catch(PDOExeption $e){
				$error = 'データベースエラー';
				return $error;
			}
		}
		
		public function confirmUser($user):?String {
			try{
				$sql = 'SELECT password FROM '.$this->table1.
					   ' WHERE email = :email';
				$sth = $this->dbh->prepare($sql);
				$sth->bindParam(':email', $user['email'], PDO::PARAM_STR);
				$sth->execute();
				
				if($result = $sth->fetch(PDO::FETCH_ASSOC)){
					if(password_verify($user['password'], $result['password'])){
						return null;
					}else{
						$error = 'パスワードが間違っています。';
						return $error;
					}
				}else{
					$error = 'パスワードが間違っています。';
						return $error;
				}
			}catch(PDOExeption $e){
				$error = 'データベースエラー';
				return $error;
			}
		}

		public function findUser($id):Array {
			$sql = 'SELECT name, kana, email, childname, childkana FROM '.
				   $this->table1.' WHERE id = :id';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':id', $id, PDO::PARAM_INT);
			$sth->execute();
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			return $result;
		}

		public function findRole($email){
			$sql = 'SELECT id, email, role FROM '.$this->table1.' WHERE email = :email';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':email', $email, PDO::PARAM_STR);
			$sth->execute();
			$result = $sth->fetch(PDO::FETCH_ASSOC);

			session_start();
			$_SESSION['email'] = $result['email'];
			$_SESSION['role'] = $result['role'];
			$_SESSION['id'] = $result['id'];
		}

		public function deleteuser($email){
			$sql = 'DELETE FROM '.$this->table1.' WHERE email = :email';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':email', $email, PDO::PARAM_STR);
			$sth->execute();
		}

		public function findAll($page=0):Array {
			$sql = 'SELECT id, name, email, childname FROM '.$this->table1.
				   ' WHERE role = 1 LIMIT 20 OFFSET '.(20 * $page);
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		public function countAll():Int {
			$sql = 'SELECT count(*) as count FROM '.$this->table1;
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			$count = $sth->fetchColumn();
			return $count;
		}

		public function updateUser($user){
			$sql = 'UPDATE '.$this->table1.
				   ' SET name=:name, kana=:kana, email=:email, 
				   childname=:childname, childkana=:childkana 
				   WHERE email=:email';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':name', $user['name'], PDO::PARAM_STR);
			$sth->bindParam(':kana', $user['kana'], PDO::PARAM_STR);
			$sth->bindParam(':email', $user['email'], PDO::PARAM_STR);
			$sth->bindParam(':childname', $user['childname'], PDO::PARAM_STR);
			$sth->bindParam(':childkana', $user['childkana'], PDO::PARAM_STR);
			$sth->execute();
		}
	}

?>