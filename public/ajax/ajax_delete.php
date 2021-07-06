<?php
	define('ROOT_PATH', str_replace('public', '', $_SERVER["DOCUMENT_ROOT"]));
	require_once(ROOT_PATH .'/Models/Db.php');

	class Diary extends Db{
		private $table1 = 'images';

		public function __construct($dbh = null){
			parent::__construct($dbh);
		}

		public function deleteImage($id){
			$sql = 'DELETE FROM '.$this->table1.' WHERE id=:id';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':id', $id, PDO::PARAM_INT);
			$sth->execute();
		}
	}

	$Diary = new Diary();
	$Diary->deleteImage($_POST['id']);

?>