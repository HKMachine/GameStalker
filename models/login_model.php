<?php

class Login_Model extends Model
{
	public function __construct()
	{		parent::__construct();
	}
public function usernameCheck(){
			if($_POST['username']){
				$user = $_POST['username'];
			}
			else{
				echo "NOT SET";
				return false;
			}
			$sth = $this->db->prepare("SELECT UserId FROM user WHERE UserName=:user");
		$sth->execute(array(
			':user' => $user
		));
		$count = $sth->rowCount();
		if($count == 0){
			echo "false";
			return false;
		}
		else{
			echo "true";
			return true;
		}
	
}
	public function run()
	{
		$sth = $this->db->prepare("SELECT UserId FROM user WHERE 
				UserName = :username AND Password = :password");
		$sth->execute(array(
			':username' => $_POST['username'],
			':password' => Hash::create('md5', $_POST['password'], HASH_PASSWORD_KEY)
		));
		
		$data = $sth->fetch();
		
		$count =  $sth->rowCount();
		if ($count > 0) {
			// login
			Session::init();
			Session::set('id', $data['UserId']);
			echo $data['UserId'];
		} 
	}
	public function logout(){
		Session::init();
		Session::destroy();
	}
	public function add(){
		$username = $_POST['username'];
		$password = Hash::create('md5', $_POST['password'], HASH_PASSWORD_KEY);
		if($_POST['xbox'] != ""){
		$xbox = $_POST['xbox'];
		}
		else{
			$xbox = NULL;
		}
		if($_POST['psn'] != ""){
			$psn = $_POST['psn'];
		}
		else{
			$psn = NULL;
		}
		$email = $_POST['email'];
		$sth = $this->db->prepare("INSERT INTO user(username,password,XboxId,PsnId) VALUES(:username,:password,:xbox ,:psn)");
		$sth->execute(array(
		':username' => $username,
		':password' => $password,
		':xbox' => $xbox,
		':psn' => $psn
		));
		$ops = $this->db->prepare("INSERT INTO ops(User) SELECT UserId From user WHERE UserName=:user");
		$ops->execute(array(
		':user' => $username,
		));
	}
	public function getPlats(){
		Session::init();
		$id = Session::get('id');
		$sth = $this->db->prepare("SELECT XboxId,PsnId,SteamId FROM user WHERE 
		UserId=:id");
		$sth->execute(array(
			':id' => $id,
		));
		$data = $sth->fetch();
		$count =  $sth->rowCount();
		if ($count > 0) {
			echo json_encode($data);
		} 
	}
	public function cs(){
		if(Session::check()){
			echo json_encode(TRUE);
		}
		else{
			echo json_encode(FALSE);
		}
	}
	
}