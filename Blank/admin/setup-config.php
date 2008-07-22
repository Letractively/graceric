<?php
define('WP_INSTALLING', true);

if (!file_exists('../gc-config-sample.php'))
	die('Sorry, I need a gc-config-sample.php file to work from. Please re-upload this file from your Graceric installation.');

$configFile = file('../gc-config-sample.php');

if (!is_writable('../')) die("Sorry, I can't write to the directory. You'll have to either change the permissions on your Graceric directory or create your gc-config.php manually.");


if (isset($_GET['step']))
	$step = $_GET['step'];
else
	$step = 0;
header( 'Content-Type: text/html; charset=utf-8' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Graceric &rsaquo; Setup Configuration File</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style media="screen" type="text/css">
	<!--
	html {
		background: #eee;
	}
	body {
		background: #fff;
		color: #000;
		font-family: Georgia, "Times New Roman", Times, serif;
		margin-left: 20%;
		margin-right: 20%;
		padding: .2em 2em;
	}

	h1 {
		color: #006;
		font-size: 18px;
		font-weight: lighter;
	}

	h2 {
		font-size: 16px;
	}

	p, li, dt {
		line-height: 140%;
		padding-bottom: 2px;
	}

	ul, ol {
		padding: 5px 5px 5px 20px;
	}
	#logo {
		margin-bottom: 2em;
	}
	.step a, .step input {
		font-size: 2em;
	}
	td input {
		font-size: 1.5em;
	}
	.step, th {
		text-align: right;
	}
	#footer {
		text-align: center;
		border-top: 1px solid #ccc;
		padding-top: 1em;
		font-style: italic;
	}
	-->
	</style>
</head>
<body>
<h1 id="logo">Graceric Blog</h1>
<?php
// Check if gc-config.php has been created
if (file_exists('../gc-config.php'))
	die("<p>The file 'gc-config.php' already exists. If you need to reset any of the configuration items in this file, please delete it first. You may try <a href='install.php'>installing now</a>.</p></body></html>");

switch($step) {
	case 0:
?>

<p>Welcome to Graceric. Before getting started, we need some information on the database. You will need to know the following items before proceeding.</p>
<ol>
	<li>Database name</li>
	<li>Database username</li>
	<li>Database password</li>
	<li>Database host</li>
	<li>Table prefix (if you want to run more than one Graceric in a single database) </li>
</ol>
<p><strong>If for any reason this automatic file creation doesn't work, don't worry. All this does is fill in the database information to a configuration file. You may also simply open <code>gc-config-sample.php</code> in a text editor, fill in your information, and save it as <code>gc-config.php</code>. </strong></p>
<p>In all likelihood, these items were supplied to you by your ISP. If you do not have this information, then you will need to contact them before you can continue. If you&#8217;re all ready, <a href="setup-config.php?step=1">let&#8217;s go</a>! </p>
<?php
	break;

	case 1:
	?>
</p>
<form method="post" action="setup-config.php?step=2">
	<p>Below you should enter your database connection details. If you're not sure about these, contact your host. </p>
	<table>
		<tr>
			<th scope="row">Database Name</th>
			<td><input name="dbname" type="text" size="25" value="graceric" /></td>
			<td>The name of the database you want to run WP in. </td>
		</tr>
		<tr>
			<th scope="row">User Name</th>
			<td><input name="uname" type="text" size="25" value="username" /></td>
			<td>Your MySQL username</td>
		</tr>
		<tr>
			<th scope="row">Password</th>
			<td><input name="pwd" type="text" size="25" value="password" /></td>
			<td>...and MySQL password.</td>
		</tr>
		<tr>
			<th scope="row">Database Host</th>
			<td><input name="dbhost" type="text" size="25" value="localhost" /></td>
			<td>99% chance you won't need to change this value.</td>
		</tr>
		<tr>
			<th scope="row">Table Prefix</th>
			<td><input name="prefix" type="text" id="prefix" value="gcdb_" size="25" /></td>
			<td>If you want to run multiple Graceric installations in a single database, change this.</td>
		</tr>
		<tr>
			<th scope="row">Charset</th>
			<td><input name="charset" type="text" id="charset" value="utf8" size="25" /></td>
			<td>Provide the database charset you will use (utf8|gb2312|...).</td>
		</tr>
	</table>
	<h2 class="step">
	<input name="submit" type="submit" value="Submit" />
	</h2>
</form>
<?php
	break;

	case 2:
	$dbname  = trim($_POST['dbname']);
	$uname   = trim($_POST['uname']);
	$passwrd = trim($_POST['pwd']);
	$dbhost  = trim($_POST['dbhost']);
	$prefix  = trim($_POST['prefix']);
	$charset  = trim($_POST['charset']);
	if (empty($prefix)) $prefix = 'gcdb_';
	if (empty($charset)) $charset = 'utf8';

	// Test the db connection.
	define('DB_NAME', $dbname);
	define('DB_USER', $uname);
	define('DB_PASSWORD', $passwrd);
	define('DB_HOST', $dbhost);

	// We'll fail here if the values are no good.
	require_once('../gc-includes/gcdb.class.php');
	$handle = fopen('../gc-config.php', 'w');

	foreach ($configFile as $line_num => $line) {
		switch (substr($line,0,16)) {
			case "define('DB_NAME'":
				fwrite($handle, str_replace("graceric", $dbname, $line));
				break;
			case "define('DB_USER'":
				fwrite($handle, str_replace("'username'", "'$uname'", $line));
				break;
			case "define('DB_PASSW":
				fwrite($handle, str_replace("'password'", "'$passwrd'", $line));
				break;
			case "define('DB_HOST'":
				fwrite($handle, str_replace("localhost", $dbhost, $line));
				break;
			case '$table_prefix  =':
				fwrite($handle, str_replace('gcdb_', $prefix, $line));
				break;
			case '$table_charset  ':
				fwrite($handle, str_replace('utf8', $charset, $line));
				break;
			default:
				fwrite($handle, $line);
		}
	}
	fclose($handle);
	chmod('../gc-config.php', 0666);
?>
<p>All right sparky! You've made it through this part of the installation. Graceric can now communicate with your database. If you are ready, time now to <a href="install.php?prefix=<?php echo($prefix);?>&charset=<?php echo($charset);?>">run the install!</a></p>
<?php
	break;
}
?>
<p id="footer"><a href="http://www.ericfish.com/graceric">Graceric</a>, will be the simplest blog ever.</p>
</body>
</html>