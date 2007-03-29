<?
require_once('../gc-config.php');
include("../gc-includes/admin-functions.php");

global $gcdb;

function gpc2sql($str) {
    if(get_magic_quotes_gpc()==1) 
        return $str;
    else 
        return addslashes($str);
}

if(isset($HTTP_POST_VARS['username']))
{
	$username = gpc2sql($HTTP_POST_VARS['username']);
	$password = gpc2sql($HTTP_POST_VARS['password']);

	if($username=="")
	{
		$info="Please input name";
		header("location:login.php?info=$info");
		exit;
	}
	else if($password=="")
	{
		$info="Please input password";
		header("location:login.php?info=$info");
		exit;
	}
	else
	{
		$request = "SELECT user_pass FROM $gcdb->users WHERE user_login='$username'"; 
		$member_password = $gcdb->get_var($request);
		if($member_password)
		{
			if($member_password!=$password)
			{
				$info="The password is not correct.";
				header("location:login.php?info=$info");
				exit;
			}
			else
			{
				$info="Login Successfully!";
				//start session
				addSession($username);
				header("location:index.php");
				exit;
			}
		}
		else
		{
			$info="Username does not exist, login fail.";
			header("location:login.php?info=$info");
			exit;
		}
	}
}
?>