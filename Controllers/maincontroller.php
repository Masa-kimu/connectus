<?php

	require_once(ROOT_PATH .'/Models/Users.php');

	class maincontroller{
		private $request;
		private $Users;

		public function __construct(){
			$this->request['get'] = $_GET;
			$this->request['post'] = $_POST;

			$this->Users = new Users();

			$dbh = $this->Users->get_db_handler();
		}

		public function index(){

		}

		public function resetpswd(){
			$error = $this->Users->resetpswd($this->request['post']);

			if(isset($error)){
				return $error;
			}else{
				$this->Users->findRole($this->request['post']['email']);
			}
		}

		public function register(){
			$error = $this->Users->registerUser($this->request['post']);
			
			if(isset($error)){
				return $error;
			}else{
				$this->Users->findRole($this->request['post']['email']);
			}
		}

		public function login(){
			$error = $this->Users->loginUser($this->request['post']);

			if(isset($error)){
				return $error;
			}else{
				$this->Users->findRole($this->request['post']['email']);
			}

		}

		public function logout(){
			session_start();
			$_SESSION = array();
			setcookie($_COOKIE[session_name()], '', time()-1);
			session_destroy();
		}

		public function userlist(){
			$page = 0;
			if(isset($this->request['get']['page'])){
				$page = $this->request['get']['page'];
			}

			if(isset($this->request['post']['submit_delete'])){
				$this->Users->deleteuser($this->request['post']['email']);
			}

			$users = $this->Users->findAll($page);
			$count = $this->Users->countAll();
			$params = ['users' => $users, 'pages' => $count / 10];
			return $params;
		}

		public function userdetail($id = null){
			if(isset($this->request['get']['id'])){
				$user = $this->Users->findUser($this->request['get']['id']);
			}

			if(isset($id)){
				$user = $this->Users->findUser($id);
			}

			return $user;
		}

		public function deleteuser():?String {
			if($this->request['post']['submit_delete_u']){
				$error = $this->Users->confirmUser($this->request['post']);
				if(empty($error)){
					$this->Users->deleteuser($this->request['post']['email']);
					return null;
				}else{
					return $error;
				}
			}else{
				$this->Users->deleteuser($this->request['post']['email']);
				return null;
			}
		}
		public function edituser(){
			$this->Users->updateUser($this->request['post']);
		}

	}

?>