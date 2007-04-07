<?
/* Graceric
*  Author: ericfish
*  File: /admin/checklogin.php
*  Usage: Check Username and Password
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id$
*  $LastChangedDate$
*  $LastChangedRevision$
*  $LastChangedBy$
*  $URL$
*/

require_once('../gc-config.php');
require_once('../gc-settings.php');

global $gcdb,$error;

function gpc2sql($str) {
    if(get_magic_quotes_gpc()==1) 
        return $str;
    else 
        return addslashes($str);
}

$username = gpc2sql($HTTP_POST_VARS['username']);
$password = gpc2sql($HTTP_POST_VARS['password']);

if(user_login($username,$password))
{
	// set cookie
	user_setcookie($username, $password);

	// redirect
	if ( isset($_REQUEST['redirect_to']) )
		//$redirect_to = preg_replace('|[^a-z0-9-~+_.?#=&;,/:]|i', '', $_REQUEST['redirect_to']);
		$redirect_to = $_REQUEST['redirect_to'];
	else
        $redirect_to = "index.php";
    user_redirect($redirect_to);
}
else
{
    header("location:login.php?info=$error");
}
?>