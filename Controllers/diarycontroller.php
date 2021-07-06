<?php

	require_once(ROOT_PATH .'/Models/Diary.php');

	class diarycontroller{
		private $request;
		private $Diary;

		public function __construct(){
			$this->request['get'] = $_GET;
			$this->request['post'] = $_POST;
			$this->request['files'] = $_FILES;

			$this->Diary = new Diary();

			$dbh = $this->Diary->get_db_handler();
		}

		public function createschedule(){
			$this->Diary->createSchdule($this->request['post']);
		}

		public function index($ym, $count_date, $id){
			if(isset($this->request['get']['del'])){
				$this->Diary->deleteSchedule($this->request['get']);
			}
			for($i=1; $i<=$count_date; $i++){
				$date = $ym. '-'. $i;
				$schedule[$date] = $this->Diary->findByDate($date);
			}
			$schedule['attend'] = $this->Diary->findAttend($id);
			return $schedule;
		}

		public function schedule(){
			if(!empty($this->request['get'])){
				$schedule = $this->Diary->findSchedule($this->request['get']);
			}else{
				$schedule = $this->Diary->findById($this->request['post']['schedule_id']);
			}
			
			return $schedule;
		}

		public function submitattend(){
			$this->Diary->submitAttend($this->request['post']);
		}

		public function findDate(){
			$dates = $this->Diary->findDate();
			return $dates;
		}

		public function editdiary(){
			$diary = ['diary' => $this->request['post'], 'files' => $this->request['files']];
			$this->Diary->updateDiary($diary);
		}

		public function diary($id){
			$contents = $this->Diary->findDiary($id);
			return $contents;
		}

		public function updatecomment(){
			$this->Diary->updateComment($this->request['post']);
		}

		public function comments($id){
			$comments = $this->Diary->findComment($id);
			return $comments;
		}
	}

?>