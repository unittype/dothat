<?php

// creating session
session_start();

// definition of DB connection
define(DB_HOST, 'localhost');
define(DB_PORT, '5432');
define(DB_PASSWD, '9891');
define(DB_USER, 'postgres');
define(DB_NAME, 'dothat');

// error array list
$errors = array(
		'db_conn' => null,
		'up' => [
			'username' => null,
			'email' => null,
			'password_1' => null,
			'password_2' => null,
			'password' => null,
			'db_query' => null
		],
		'in' => [
			'email' => null,
			'password' => null,
			'backup' => null
		]
	); 

// try to make connection with DB
try {
	$db = pg_connect('host='.DB_HOST.' port='.DB_PORT.' password='.DB_PASSWD.' user='.DB_USER.' dbname='.DB_NAME.'');
} catch (Exception $e) {
	$array['up']['db_conn'] = 'Caught exception: '.$e->getMessage();
}

// initialization usernames and emails into array for checking only
$sql = 'select username, email, password from users';
$process = pg_query($db, $sql);

$maillist = array();

$row = pg_fetch_all($process);
foreach ($row as $key => $value) {
	array_push($maillist, $value);
}
//echo json_encode($maillist[0]['username']);
/*
*
*	dothat AES methods [dothat_AES.enc(str)] or [dothat_AES.dec(str)] 
*
*/
class dothat_AES {

	static private $k = 'ff4de0a1a68f8755769b681c4bcff9bdb5ca729fe331a357883082c68ca49f6e'; // Encryption Decryption Private Key

	public function enc( $a ) {	    
	    return(base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $this::$k ), $a, MCRYPT_MODE_CBC, md5( md5( $this::$k ) ) ) ) );
	}

	public function dec( $a ) {
	    return(rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $this::$k ), base64_decode( $a ), MCRYPT_MODE_CBC, md5( md5( $this::$k ) ) ), "\0") );
	}	
}
$AES = new dothat_AES();
/*
*
* Sign Up request handler
*
*/

if (isset($_POST['signup'])) {
	$username = pg_escape_string($db, $_POST['identity']);
	$email = pg_escape_string($db, $_POST['enmail']);
	$password_1 = pg_escape_string($db, $_POST['enpw']);
	$password_2 = pg_escape_string($db, $_POST['cfpw']);
	unset($_SESSION['error_list']);
/*
	$u = '/^([a-zA-Z0-9_.]*)$/';
	$m = '/^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/';
	$p = '/^([a-zA-Z0-9_%@!?]*)+$/';

	preg_match($u, $username, $match1, PREG_OFFSET_CAPTURE);
	preg_match($m, $email, $match2, PREG_OFFSET_CAPTURE);
	preg_match($p, $password_1, $match3, PREG_OFFSET_CAPTURE);
	
	if(!($match1[1] && $match2 && $match3 && $password_1 != $password_2)) {
		header('location : /warning.html');
	}
*/

	if (empty($username)) { $errors['up']['username'] = "Username is required"; }
	if (empty($email)) { $errors['up']['email'] ="Email is required"; }
	if (empty($password_1)) { $errors['up']['password_1'] = "Password is required"; }
	if (empty($password_2)) { $errors['up']['password_2'] = "Password should confirm!"; }

	if ($password_1 != $password_2) {
		$errors['up']['password'] = "The two passwords do not match";
	}

	if (array_values($errors['up']) != null) {
		$hash_uname = $AES->enc($username);
		$hash_mail = $AES->enc($email);
		$hash_pw = $AES->enc($password_1);
		
		try {
			$query_begin = "begin";
			pg_query($db, $query_begin);
			$query_sql = "INSERT INTO users (username, email, password) VALUES('$hash_uname', '$hash_mail', '$hash_pw')";
			pg_query($db, $query_sql);
			$query_commit = "commit";
			pg_query($db, $query_commit);
			$_SESSION['username'] = $username;
		} catch (Exception $e) {
			$errors['up']['db_query'] = 'Caught exception: '.$e->getMessage();
			$_SESSION['error_list'] = $errors['up'];
			header('location: /');
		}
		header('location: /main/');
	}
}

if (isset($_GET['signin'])) {
	$email = pg_escape_string($db, $_GET['an']);
	$password = pg_escape_string($db, $_GET['pw']);
	unset($_SESSION['error_list']);
	if (empty($email)) {
		$errors['in']['email'] = "Email is required";
	}
	if (empty($password)) {
		$errors['in']['password'] = "Password is required";
	}
	if (array_values($errors['in']) != null) {
		$input = $password;
		$hash_mail = $AES->enc($email);
		$hash_pw = $AES->enc($input);
		try {
			pg_query($db, "begin");
			$query = "SELECT * FROM users WHERE email='$hash_mail' AND password='$hash_pw'";				
			$results = pg_query($db, $query) or die(pg_error($db));			
			pg_query($db, "commit");
		} catch (Exception $e) {
			$errors['up']['db_query'] = 'Caught exception: '.$e->getMessage();
			$_SESSION['error_list'] = $errors['up'];
			header('location: /');
		}
		if (pg_num_rows($results) == 1) {
			$row = pg_fetch_all($results);
			$user_info = array();
			foreach ($row as $key => $value) {
				array_push($user_info, $value);
			}
			$_SESSION['username'] = $AES->dec($user_info[0]['username']);
			header('location: /');
		}else {
			$errors['in']['backup'] = "Wrong email/password combination";
			$_SESSION['error_list'] = $errors['in'];
			header('location: /');
		}

	}
}
?>